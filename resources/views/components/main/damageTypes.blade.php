<div class="columns has-text-white p-3">
    <div class="column is-dark-1 p-5">
        <div class="content">
            <h3 class="title has-text-white">Damage Types</h3>

            <div class="columns">
                <div class="column is-size-4 has-text-warning">Enemy</div>
                <div class="column is-size-4 has-text-warning">Use Hardeners</div>
                <div class="column is-size-4 has-text-warning">Use Damage</div>
            </div>
            @foreach ($types as $item)
                <div class="columns">
                    <div class="column py-0">{{ $item[0] }}</div>
                    <div class="column py-0">{{ $item[1] }}</div>
                    <div class="column py-0">{{ $item[2] }}</div>
                </div>
            @endforeach

        </div>
    </div>
</div>