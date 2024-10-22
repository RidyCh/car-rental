<div>
    <div class="flex flex-wrap justify-center mb-12 -mx-3">
        @foreach ($topCars as $car)
            <div class="w-full p-3 md:w-6/12 lg:w-4/12">
                <div class="text-gray-500 bg-white border shadow-md">
                    <a href="{{ route('car.show', $car->slug) }}"><img
                            src="{{ asset('storage/car-images/' . $car->image) }}" class="w-full hover:opacity-90"
                            alt="{{ $car->name }}" width="600" height="450" /></a>
                    <div class="p-6">
                        <h4 class="mb-2 text-xl font-bold text-gray-900"><a href="{{ route('car.show', $car->slug) }}"
                                class="hover:text-gray-500">{{ $car->name }} {{ $car->year }}</a></h4>
                        <p class="mb-2 text-sm">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                        </p>
                        <hr class="my-4 border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="inline-flex items-center py-1 space-x-1">
                                <span>4.7</span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    width="1.125em" height="1.125em" class="text-primary-500">
                                    <g>
                                        <path fill="none" d="M0 0h24v24H0z"></path>
                                        <path
                                            d="M12 18.26l-7.053 3.948 1.575-7.928L.587 8.792l8.027-.952L12 .5l3.386 7.34 8.027.952-5.935 5.488 1.575 7.928z">
                                        </path>
                                    </g>
                                </svg>
                                <span>(245 reviews)</span>
                            </div>
                            <p class="font-bold text-gray-900">Rp{{ number_format($car->price, 2, ',', '.') }}/hari</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
