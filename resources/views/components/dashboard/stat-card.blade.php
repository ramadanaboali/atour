@props([
'route' => '#',
'icon' => 'users',
'color' => 'primary',
'value' => 0,
'label' => '',
])

<div class="col-xl-2 col-md-4 col-sm-6">
    <div class="card text-center">
        <a href="{{ $route }}">
            <div class="card-body">
                <div class="avatar bg-light-{{ $color }} p-50 mb-1">
                    <div class="avatar-content">
                        <i data-feather="{{ $icon }}" class="font-medium-5"></i>
                    </div>
                </div>
                <h2 class="fw-bolder">{{ $value }}</h2>
                <p class="card-text">{{ $label }}</p>
            </div>
        </a>
    </div>
</div>
