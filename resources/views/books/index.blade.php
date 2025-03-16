@extends('layout.app')
@section('content')
    <h1 class="text-2xl mb-5 font-bold">Book List</h1>

    <form class="flex gap-2 mb-4" action="{{ route('books.index') }}" method="GET">
        <input class='input' type="search" name="title" id="title" value='{{ request('title') }}'
            placeholder="Search By Title">
            <input type="hidden" name="filter" value="{{request('filter')}}">
        <button class="btn" type="submit">Search</button>
        <a  href='{{route('books.index')}}' class="btn">Reset</a>
    </form>

    @php
        $filters = [
            '' => 'Latest Books',
            'popular-last-month' => 'Popular Last Month',
            'popular-last-6months' => 'Popular Last 6 Months',
            'high-rated-last-month' => 'High Rated Last Month',
            'high-rated-last-6months' => 'High Rated Last 6 Months',
        ];

    @endphp
    <div class="filter-container">
        @foreach ($filters as $key => $value)
            <a class='{{ $key == Request('filter') || ($key == '' && Request('filter') == null) ? 'filter-item-active' : 'filter-item' }}'
                href="{{ route('books.index', [...Request()->query(), 'filter' => $key]) }}">
                {{ $value }}
            </a>
        @endforeach
    </div>

    <ul>
        @forelse ($books as $book)
            <li class="mb-4">
                <div class="book-item">
                    <div class="flex flex-wrap items-center justify-between">
                        <div class="w-full flex-grow sm:w-auto">
                            <a href="{{ route('books.show', $book) }}" class="book-title">{{ $book->title }}</a>
                            <span class="book-author">{{ $book->author }}</span>
                        </div>
                        <div>
                            <x-star-component :rating="$book->reviews_avg_rating"/>
                            <div class="book-rating">

                                {{ number_format($book->reviews_avg_rating, 1) }}
                            </div>
                            <div class="book-review-count">
                                out of {{ $book->reviews_count }} {{ Str::plural('review', $book->reviews_count) }}
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        @empty
            No Books
        @endforelse
    </ul>
@endsection
