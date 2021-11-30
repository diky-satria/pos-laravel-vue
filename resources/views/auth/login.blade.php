@extends('layouts.auth')

@section('content')
<div class="container">
   <div class="row justify-content-center">
         <div class="col-lg-5">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
               <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
               <div class="card-body">
                     <form method="POST" action="{{ route('login') }}">

                        @csrf
                        <div class="mb-3">
                           <label class="form-label">Email</label>
                           <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email..." autofocus />
                           @error('email')
                              <div class="form-text text-danger">
                                 {{ $message }}
                              </div>
                           @enderror
                        </div>

                        <div class="mb-3">
                           <label class="form-label">Password</label>
                           <input id="password" type="password" class="form-control" name="password" placeholder="Password" />
                           @error('password')
                              <div class="form-text text-danger">
                                 {{ $message }}
                              </div>
                           @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm float-end">Login</button>
                     </form>
               </div>
            </div>
         </div>
   </div>
</div>
@endsection