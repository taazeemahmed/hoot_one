<x-app-layout>
    <div id="vue-app">
        <marketing-lead-create
            :representatives="{{ json_encode($representatives) }}"
        ></marketing-lead-create>
    </div>
</x-app-layout>
