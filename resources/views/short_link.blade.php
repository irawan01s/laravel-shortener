<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Shortener Url</title>
  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}" defer></script>

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
  <div class="container">

    <h1 class="d-flex justify-content-center mt-5">Shortener URL Laravel 7</h1>
    <form method="POST" action="{{ route('shorten.store') }}">
      @csrf
      <div class="input-group mx-auto col-8 mb-3 mt-5">
        <input type="text" name="link" class="form-control{{ $errors->has('link') ? ' is-invalid' : '' }}"
          placeholder="Input Url" aria-label="Input Url" aria-describedby="btn-input-url" value="{{ old('link') }}">
        <div class="input-group-append">
          <button class="btn btn-primary" type="submit" id="btn-input-url">Generate Link</button>
        </div>
        <div class="invalid-feedback">{{ $errors->first('link') }}</div>
      </div>
    </form>

    <div class="card shadow">
      <div class="card-body">
        @if (Session::has('success'))
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
          {{ Session::get('success') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        @endif
        <div class="table-responsive-lg">
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="row">Id</th>
                <th scope="row" class="w-25">Short Link</th>
                <th scope="row" class="w-75">Link</th>
                <th class="text-center w-25">Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($shortLinks as $link)
              <tr>
                <td>{{ $link->id }}</td>
                <td>
                  <a href="{{ route('shorten.show', $link->shortcode) }}" target="_blank">{{ $link->shortcode }}</a>
                </td>
                <td>{{ $link->url }}</td>
                <td> 
                  <form action="{{ route('shorten.destroy', $link->id)}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger" type="submit"><i class="fas fa-trash"></i></button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</body>

</html>