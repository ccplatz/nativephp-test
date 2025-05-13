@extends('components.layouts.transparent')

@section('content')
    <div>
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
        <script>
            confetti({
                particleCount: 100,
                spread: 70,
                origin: {y: 0.6}
            });
        </script>
        <script>
            setTimeout(() => {
                window.close();
            }, 3000);
        </script>
    </div>
@endsection
