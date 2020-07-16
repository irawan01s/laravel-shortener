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
    <form method="POST" action="{{ url('generate-shorten') }}">
      @csrf
      <div class="input-group mb-3">
        <input type="text" name="link" class="form-control{{ $errors->has('link') ? 'is-invalid' : '' }}" placeholder="Input Link" aria-label="Input Link"
          aria-describedby="btn-input-url" value="{{ old('link') }}">
        <div class="input-group-append">
          <button class="btn btn-primary" type="submit" id="btn-input-url">Generate Link</button>
        </div>
        <div class="invalid-feedback">{{ $errors->first('link') }}</div>
      </div>
    </form>

    <div class="card shadow">
      <div class="card-body">
        @if (Session::has('success'))
        <div class="alert alert-success">
          <p>{{ Session::get('success') }}</p>
        </div>
        @endif

        <table class="table table-striped table-sm">
          <thead class="thead-dark">
            <tr>
              <th>ID</th>
              <th>Short Link</th>
              <th>Link</th>
            </tr>
          </thead>
          <tbody>
            @foreach($shortLinks as $link)
            <tr>
              <td>{{ $link->id }}</td>
              <td>
                <a href="{{ route('short.link', $link->code) }}" target="_blank">{{ route('short.link', $link->code) }}</a>
              </td>
              <td>{{ $link->link }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

  </div>
</body>

</html>