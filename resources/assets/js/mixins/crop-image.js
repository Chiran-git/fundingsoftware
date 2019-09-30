module.exports = {
    data () {
        return {
            originalImageFiles: {},
            croppedImageFiles: {},
            field: null,
        }
    },

    methods: {
        initializeCrop (file, name, parent, index = '') {
            let image = document.getElementById(`${name}-crop${index}-img`);
            image.src = URL.createObjectURL(file);

            let $modal = $(`#${name}-crop${index}`);
            let cropper = null;

            let vueInstance = this;
            let origFile = null;
            if (index === '') {
                origFile = vueInstance.originalImageFiles[name];
            } else {
                origFile = vueInstance.originalImageFiles[name][index];
            }

            image.onload = () => {
                // We will do image dimension validation on client side because the
                // cropper will always resize smaller images to the required ones. Hence
                // can't do validation on the server
                if (image.naturalWidth < origFile.requiredWidth
                    || image.naturalHeight < origFile.requiredHeight) {
                        let error = `The image should be at least ${origFile.requiredWidth} x ${origFile.requiredHeight} pixels.`;
                        this.$root.eMessage(error);
                        $modal.modal('hide');
                        vueInstance.resetFileInput(name, index);
                } else {
                    $modal.modal('show');
                    // Initiate the cropper only after the modal is ready
                    // We are not doing it in modal.show event because it is causing
                    // issues when modal is closed and shown multiple times
                    setTimeout(() => {
                        cropper = new Cropper(image, {
                            aspectRatio: origFile.requiredWidth / origFile.requiredHeight,
                            viewMode: 1,
                        });

                        Vue.set(origFile, 'file', file);
                        Vue.set(origFile, 'cropper', cropper);
                        Vue.set(origFile, 'parent', parent);
                    }, 1000);
                }
            };
        },

        destroyCropper (name, index = '') {
            let $modal = $(`#${name}-crop${index}`);
            $modal.modal('hide');
            let origFile;
            if (index === '') {
                origFile = this.originalImageFiles[name];
            } else {
                origFile = this.originalImageFiles[name][index];
            }

            origFile.cropper.destroy();
            origFile.cropper = null;
            origFile.file = null;
        },

        resetFileInput (name, index = '') {
            let input;

            if (index === '') {
                input = this.$refs[name];
            } else {
                input = this.$refs[name][index];
            }

            input.type = 'text';
            input.type = 'file';
        },

        cropImage(name, index = '') {
            let origFile;
            if (index === '') {
                origFile = this.originalImageFiles[name];
            } else {
                origFile = this.originalImageFiles[name][index];
            }

            let canvas = origFile.cropper.getCroppedCanvas({
                width: origFile.requiredWidth,
                height: origFile.requiredHeight,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            });

            let vueInstance = this;

            canvas.toBlob(function (blob) {
                origFile.parent[name] = URL.createObjectURL(blob);
                vueInstance.setImageStyles();
                let $modal = $(`#${name}-crop${index}`);
                $modal.modal('hide');

                if (index === '') {
                    Vue.set(vueInstance.croppedImageFiles, name, blob);
                } else {
                    if (_.isUndefined(vueInstance.croppedImageFiles[name])
                        || ! _.isArray(vueInstance.croppedImageFiles[name])) {
                        Vue.set(vueInstance.croppedImageFiles, name, []);
                    } else {
                        Vue.set(vueInstance.croppedImageFiles, name, []);

                    }
                    vueInstance.croppedImageFiles[name][index] = blob;
                }

                vueInstance.destroyCropper(name, index);
            });
        },
    },
}
