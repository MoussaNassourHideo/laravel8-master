<div class="mb-2 mt-4">
    @auth
        <form action="{{ $route }}" method="POST">
              @csrf
        
             <div class="form-group">
                  <textarea type="text" class="form-control"  name="content" ></textarea>
            </div>
           <div class="mt-4"><input type="submit"  class="btn btn-primary btn-block">  Add comment</div>
          </form>
          {{-- @errors
          @enderrors --}}
    @else
        <a href="{{ route('login') }}">to post comments!</a>
    @endauth
    </div>
    <hr/>