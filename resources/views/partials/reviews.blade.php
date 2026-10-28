<div class="reviews-list">
    @if($reviews->count() > 0)
        <div class="avg-rating-wrapper">
            @php $avg = $business->reviews()->avg('rating'); @endphp
            @for($i=1; $i<=5; $i++)
                {{ $i <= round($avg) ? '★' : '☆' }}
            @endfor
            <span class="avg-rating-text">({{ number_format($avg, 1) }} / 5)</span>
        </div>

        @foreach($reviews as $review)
            <div class="review-item" id="review-{{ $review->id }}">
                <div class="review-header">
                    <strong>{{ $review->user->name }}</strong>
                    <span class="review-stars">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                </div>
                
                <p id="comment-text-{{ $review->id }}" class="review-text">{{ $review->comment }}</p>

                @auth
                    @if($review->user_id === auth()->id())
                        <div class="review-actions">
                            <button onclick="toggleEdit({{ $review->id }})" class="action-btn edit-btn">
                                <i class="fas fa-edit"></i> Επεξεργασία
                            </button>
                            <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Διαγραφή σχολίου;');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete-btn">
                                    <i class="fas fa-trash"></i> Διαγραφή
                                </button>
                            </form>
                        </div>
                        <form id="edit-form-{{ $review->id }}" action="{{ route('reviews.update', $review->id) }}" method="POST" class="edit-form">
                            @csrf
                            @method('PUT')
                            <div class="star-rating">
                                @for($i=5; $i>=1; $i--)
                                    <input type="radio" id="edit-star{{$i}}-{{$review->id}}" name="rating" value="{{$i}}" {{ $review->rating == $i ? 'checked' : '' }} />
                                    <label for="edit-star{{$i}}-{{$review->id}}">★</label>
                                @endfor
                            </div>
                            <textarea name="comment" rows="2" class="edit-textarea">{{ $review->comment }}</textarea>
                            <button type="submit" class="back-btn update-btn">Ενημέρωση</button>
                            <button type="button" onclick="toggleEdit({{ $review->id }})" class="cancel-btn">Ακύρωση</button>
                        </form>
                    @endif
                @endauth
                <small class="review-date">{{ $review->created_at->format('d/m/Y') }}</small>
            </div>
        @endforeach

        <div class="pagination-wrapper">
            {{ $reviews->fragment('reviews-container')->links() }}
        </div>

    @else
        <p>Δεν υπάρχουν ακόμα αξιολογήσεις.</p>
    @endif
    
</div>
