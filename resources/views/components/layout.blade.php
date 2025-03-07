@include('partials.head')

@include('partials.nav')

<!-- Main Content -->
<main class="container mx-auto p-4 flex-1">
   {{$slot}}
</main>

@include('partials.footer')