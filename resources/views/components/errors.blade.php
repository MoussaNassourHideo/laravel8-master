@if ($errors->any())
    <div class="mb-3 mt-2">
            @foreach ($errors->all() as $error )
                <div 
                class="aler alert-danger" role="alert"> 
                {{ $error }}
                </div>
            @endforeach
    </div>
@endif