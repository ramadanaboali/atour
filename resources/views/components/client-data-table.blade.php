@props([
'title' => '',
'headers' => [],
'tableClass' => ''
])

<div class="card">
    @if($title)
    <h4 class="card-header">{{ $title }}</h4>
    @endif

    <div class="card-body">
        <div class="table-responsive">
            <table class="invoice-table table text-nowrap {{ $tableClass }}">
                <thead>
                    <tr>
                        @foreach($headers as $header)
                        <th>{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
