@extends('Layouts.master')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/css/hierarchical-checkboxes.css') }}">
@endsection
@section('content')
    <div class="hierarchy-checkboxes" rel="example">
        <input class="hierarchy-root-checkbox" type="checkbox" checked="checked">
        <label class="hierarchy-root-label">Root</label>
        <div class="hierarchy-root-child hierarchy-node">
            <div class="hierarchy-node">
                <input class="hierarchy-checkbox" type="checkbox">
                <label class="hierarchy-label">Node</label>
                <div class="hierarchy-node leaf">
                    <input class="hierarchy-checkbox" type="checkbox">
                    <label class="hierarchy-label">Node (Leaf)</label>
                </div>
                <div class="hierarchy-node leaf">
                    <input class="hierarchy-checkbox" type="checkbox">
                    <label class="hierarchy-label">Node (Leaf)</label>
                </div>
                <div class="hierarchy-node">
                    <input class="hierarchy-checkbox" type="checkbox">
                    <label class="hierarchy-label">Node</label>
                    <div class="hierarchy-node leaf">
                        <input class="hierarchy-checkbox" type="checkbox">
                        <label class="hierarchy-label">Node (Leaf)</label>
                    </div>
                    <div class="hierarchy-node leaf">
                        <input class="hierarchy-checkbox" type="checkbox">
                        <label class="hierarchy-label">Node (Leaf)</label>
                    </div>
                    <div class="hierarchy-node leaf">
                        <input class="hierarchy-checkbox" type="checkbox">
                        <label class="hierarchy-label">Node (Leaf)</label>
                    </div>
                </div>
                <div class="hierarchy-node leaf">
                    <input class="hierarchy-checkbox" type="checkbox">
                    <label class="hierarchy-label">Node (Leaf)</label>
                </div>
            </div>
            <div class="hierarchy-node leaf">
                <input class="hierarchy-checkbox" type="checkbox">
                <label class="hierarchy-label">Node (Leaf)</label>
            </div>
            <div class="hierarchy-node leaf">
                <input class="hierarchy-checkbox" type="checkbox">
                <label class="hierarchy-label">Node (Leaf)</label>
            </div>
            <div class="hierarchy-node leaf">
                <input class="hierarchy-checkbox" type="checkbox">
                <label class="hierarchy-label">Node (Leaf)</label>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/js/hierarchical-checkboxes.js') }}"></script>
    <script>
        jQuery('.hierarchy-checkboxes[rel=example]').on('checkboxesUpdate',function(e, data){
            console.log("Changed!", data);
        });

    </script>
@endsection
