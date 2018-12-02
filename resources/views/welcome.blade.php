<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"/>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

    <form action="{{route('post')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="file">File input</label>
            <input type="file" id="file" name="info['logo']"/>
        </div>
        <div class="form-group">
            <label for="file2">File input</label>
            <input type="file" id="file2" name="info['icon']"/>
        </div>
      <button type="submit" class="btn btn-default">Submit</button>
  </form>

</body>
</html>
