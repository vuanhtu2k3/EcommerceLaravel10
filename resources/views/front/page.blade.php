 @extends('front.layouts.app')

 @section('content')
     <section class="section-10">
         <div class="container">
             <h1 class="my-3">{{ $page->name }}</h1>
             {!! $page->content !!}
         </div>
     </section>
 @endsection
