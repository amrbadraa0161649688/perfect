<div class="d-flex justify-content-between align-items-center">
    <button type="button" class="btn btn-primary mb-2" id="add_note">
        <i class="fe fe-plus"></i>
        @lang('home.add_note')
    </button>
</div>

<div class="card" id="add_note_form" style="display: none">
    <div class="card-body">

        <form class="card" action="{{ route('notes.store') }}" method="post">
            @csrf
            {{ $slot }}
            <div class="card-body">
                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="message-text"
                                   class="col-form-label"> @lang('home.attachment_data')</label>
                            <textarea class="form-control" name="notes_data" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <button type="submit" class="btn btn-secondary mr-2">@lang('home.save')</button>
                </div>
            </div>
        </form>
    </div>
</div>
