@component('mail::layout')
    @slot('header')
        @component('mail::header', ['url' => '#'])
        @endcomponent
    @endslot
    <h2>Dear {{ $session->student->first_name }}</h2>
    <p>You have a session scheduled with {{ $user->first_name }}
        at {{ $session->start_time->format('Y-m-d H:i') }}.</p>
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}
        @endcomponent
    @endslot
@endcomponent
