<x-app-layout>
    <div id="vue-app">
        <marketing-lead-list
            :leads="{{ json_encode($leads ?? ['data' => [], 'total' => 0, 'links' => []]) }}"
            :representatives="{{ json_encode($representatives ?? []) }}"
            :current-status="'{{ request('status') }}'"
            :current-quality="'{{ request('quality') }}'"
            :hot-count="{{ $hotCount ?? 0 }}"
            :new-count="{{ $newCount ?? 0 }}"
            :assigned-count="{{ $assignedCount ?? 0 }}"
        ></marketing-lead-list>
    </div>
</x-app-layout>
