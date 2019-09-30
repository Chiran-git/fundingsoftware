
Vue.component('admin-users', {

    data: function() {
        return  {
            options: [],
            selected: [],
            selectAll: false
        }
    },

    mounted() {
        this.options =  [
            'foo',
            'bar',
            'baz'
          ]
    },

    methods: {
        select() {
		}
    }
});
