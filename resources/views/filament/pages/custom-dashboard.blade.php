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
    <div class="mt-1">
        <h2 class="text-xl font-bold">Pengumuman</h2>
        <div class="bg-white shadow p-4 rounded-lg">
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-4 py-2">Judul</th>
                        <th class="border border-gray-300 px-4 py-2">Tanggal</th>
                        <th class="border border-gray-300 px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($announcements as $announcement)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2">{{ $announcement->title ?? 'Tanpa Judul' }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $announcement->created_at->format('d M Y') }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                    <x-filament::button 
                                        type="button" 
                                        color="primary"
                                        onclick="showModal({{ $announcement->id }})">
                                        Buka
                                    </x-filament::button>                           
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
<!-- ✅ Modal untuk Detail Pengumuman -->
  <div id="announcementModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white w-11/12 max-w-md p-4 rounded-lg shadow-lg flex flex-col max-h-[80vh]">
            <!-- Header Modal -->
            <div class="flex justify-between items-center border-b pb-2">
                <h2 id="modalTitle" class="text-lg font-bold"></h2>
                <x-filament::button color="danger" onclick="closeModal()">
                    &times;
                </x-filament::button>
            </div>

            <!-- ✅ Area Scroll dengan Pagination -->
            <div id="modalContent" class="overflow-y-auto flex-grow p-2 max-h-[60vh] text-gray-700 text-sm"></div>

            <!-- ✅ Tombol Navigasi -->
            <div class="flex justify-between mt-3">
                <x-filament::button color="secondary" id="prevPage" onclick="prevPage()">
                    &larr; Sebelumnya
                </x-filament::button>
                <x-filament::button color="primary" id="nextPage" onclick="nextPage()">
                    Selanjutnya &rarr;
                </x-filament::button>
            </div>
        </div>
    </div>




 
    @endif
    <div class="flex flex-col items-center justify-center">
        <img src="{{ $dashboardImage }}" 
            alt="Dashboard Image" 
            class="rounded-lg shadow-lg w-full max-w-md md:max-w-sm lg:max-w-xs h-auto object-cover mb-4">
    </div>
    @php
    $announcementData = $announcements->map(function ($a) {
        return [
            'id' => $a->id,
            'title' => $a->title,
            'content' => $a->content, // ✅ Pastikan HTML RichEditor tetap utuh
        ];
    })->toArray();
    @endphp

    <script>
        let announcements = @json($announcementData);

        let currentPage = 0;
        let totalPages = 0;
        let currentContent = [];

        function showModal(id) {
            let announcement = announcements.find(a => a.id === id);
            if (!announcement) return;

            document.getElementById("modalTitle").innerText = announcement.title || "Tanpa Judul";

            // ✅ Pecah konten menjadi halaman (500 karakter per halaman)
            currentContent = splitIntoPages(announcement.content, 1000);
            totalPages = currentContent.length;
            currentPage = 0;
            updateModalContent();
            
            document.getElementById("announcementModal").classList.remove("hidden");
        }

        function updateModalContent() {
            if (currentContent.length > 0) {
                document.getElementById("modalContent").innerHTML = currentContent[currentPage]; // ✅ Gunakan innerHTML untuk tampilan RichEditor
            }

            // ✅ Atur visibilitas tombol pagination
            document.getElementById("prevPage").style.visibility = currentPage > 0 ? "visible" : "hidden";
            document.getElementById("nextPage").style.visibility = currentPage < totalPages - 1 ? "visible" : "hidden";
        }

        function nextPage() {
            if (currentPage < totalPages - 1) {
                currentPage++;
                updateModalContent();
            }
        }

        function prevPage() {
            if (currentPage > 0) {
                currentPage--;
                updateModalContent();
            }
        }

        function closeModal() {
            document.getElementById("announcementModal").classList.add("hidden");
        }

        // ✅ Fungsi untuk membagi teks menjadi beberapa halaman
        function splitIntoPages(text, charsPerPage) {
            let words = text.split(' ');
            let pages = [];
            let page = '';

            for (let word of words) {
                if ((page + word).length < charsPerPage) {
                    page += word + ' ';
                } else {
                    pages.push(page.trim());
                    page = word + ' ';
                }
            }
            if (page.trim()) pages.push(page.trim());

            return pages;
        }
    </script>

</x-filament::page>
