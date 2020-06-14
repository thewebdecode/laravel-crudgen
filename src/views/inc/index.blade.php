@php echo "@extends('layouts.app')\n\n"; @endphp
@php echo "@section('title', 'title')\n"; @endphp

@php echo "@section('scopedCss')\n"; @endphp
    {{ $echoStarter }}-- Styles for this page only --}}
@php echo "@endsection\n"; @endphp

@php echo "@section('content')\n"; @endphp
    
@php echo "@endsection\n"; @endphp

@php echo "@section('scopedJs')\n"; @endphp
    {{ $echoStarter }}-- Scripts for this page only --}}
@php echo "@endsection"; @endphp