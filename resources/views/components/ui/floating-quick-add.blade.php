<div class="lp-floating-add">
    <button class="lp-floating-main" type="button" id="quickAddToggle">
        <i class="fa-solid fa-plus"></i>
    </button>

    <div class="lp-floating-menu" id="quickAddMenu">
        <a href="{{ route('companies.create') }}">
            <i class="fa-solid fa-building"></i>
            Company
        </a>

        <a href="{{ route('calendar.index') }}">
            <i class="fa-solid fa-calendar-days"></i>
            Meeting
        </a>

        <a href="{{ route('calendar.index') }}">
            <i class="fa-solid fa-list-check"></i>
            Task
        </a>

        <a href="{{ route('calendar.index') }}">
            <i class="fa-solid fa-phone"></i>
            Call
        </a>
    </div>
</div>