<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>
<body>
    <h1>Đây là List Danh sách Sản Phẩm</h1>
    <table class="table">
        <tr>
            <th>Name</th>
            <th>Image</th>
            <th>Price</th>
            <th>#</th>
        </tr>
        @foreach($products as $pro)
            <tr>
                <th>{{$pro->name}}</th>
                <th><img src="{{$pro->primary_image->standard_url}}" alt="" width="70"></th>
                <th>{{$pro->price}}</th>
            </tr>
        
        @endforeach
    </table>
    
   

</body>
</html>