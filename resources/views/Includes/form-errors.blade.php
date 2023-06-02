@if($errors->any())
    <div class="col alert alert-danger m-2">
        <ul style="list-style: none;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
