@if (session('successMessage'))
<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
    {{ session('successMessage') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
@if (session('errorMessage'))
<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
    {{ session('errorMessage') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
@if (session('warningMessage'))
<div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
    {{ session('warningMessage') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
@if (session('infoMessage'))
<div class="alert alert-info alert-dismissible fade show mt-3" role="alert">
    {{ session('infoMessage') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
