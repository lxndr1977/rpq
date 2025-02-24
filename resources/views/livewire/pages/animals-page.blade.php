<div>
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    <ul>
        @foreach ($animals as $animal)
        <li> {{$animal->name}}</li>
        @endforeach
    </ul>
</div>
