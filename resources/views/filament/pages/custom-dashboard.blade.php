<x-filament::page>
    @if(auth()->user()->role === 'admin')
<div class="mt-1">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Data Terbaru</h2>
    <div class="bg-white dark:bg-gray-800 shadow p-4 rounded-lg">
        <ul>
            @foreach(\App\Models\Supervision::with([
                'classroomPeriodTeacherSubjectRelation', 
                'classroomPeriodTeacherSubjectRelation.teacherSubjectRelation',
                'classroomPeriodTeacherSubjectRelation.teacherSubjectRelation.teacher'
            ])->latest()->take(5)->get() as $supervision)
                <li class="border-b py-2 text-gray-900 dark:text-gray-200">
                    {{ $supervision->name }} - 
                    {{ $supervision->classroomPeriodTeacherSubjectRelation->teacherSubjectRelation->teacher->name }} - 
                    {{ $supervision->created_at->format('d M Y H:i:s') }}
                </li>
            @endforeach
        </ul>
    </div>
</div>

    @else
    <div class="mt-1">
        <h2 class="text-xl font-bold">Petunjuk Penggunaan</h2>
        <div class="bg-white shadow p-4 rounded-lg">    
            <div class="mt-4 space-y-2">
                @if($manual_book)
                    <div class="mt-4">
                        <a target="_blank"  style="color: blue" href="{{ asset('storage/' . $manual_book) }}" target="_blank" class="text-blue-500 underline">
                            📖 Download Manual Book
                        </a>
                    </div>
                @else
                    <p class="text-gray-600 mt-4">Manual Book belum tersedia.</p>
                @endif                
            </div>
        </div>
    </div>        
    @endif
<div class="flex flex-col items-center justify-center">
    <img src="{{ $dashboardImage }}" 
         alt="Dashboard Image" 
         class="rounded-lg shadow-lg w-full max-w-md md:max-w-sm lg:max-w-xs h-auto object-cover mb-4">
</div>

</x-filament::page>
