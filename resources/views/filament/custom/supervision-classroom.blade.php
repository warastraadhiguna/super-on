@if ($relation)
    <div class="bg-gray-100 p-4 rounded-md mb-4">
        <h2 class="text-lg font-semibold text-gray-700 mb-2">🏫 Informasi Pengajaran</h2>
        
        <div class="bg-white p-4 rounded-lg shadow">
            <table class="w-full">
                <tbody>
                    <tr>
                        <td class="font-medium text-gray-700 w-1/4 py-2">Kelas</td>
                        <td class="text-gray-900 py-2">:</td>
                        <td class="text-gray-900 py-2">{{ $relation->classroom->name ?? 'Tidak ada data' }}</td>
                    </tr>
                    <tr>
                        <td class="font-medium text-gray-700 w-1/4 py-2">Periode</td>
                        <td class="text-gray-900 py-2">:</td>
                        <td class="text-gray-900 py-2">{{ $relation->period->name ?? 'Tidak ada data' }}</td>
                    </tr>
                    <tr>
                        <td class="font-medium text-gray-700 w-1/4 py-2">Guru</td>
                        <td class="text-gray-900 py-2">:</td>
                        <td class="text-gray-900 py-2">{{ $relation->teacherSubjectRelation->teacher->name ?? 'Tidak ada data' }}</td>
                    </tr>
                    <tr>
                        <td class="font-medium text-gray-700 w-1/4 py-2">Mata Pelajaran</td>
                        <td class="text-gray-900 py-2">:</td>
                        <td class="text-gray-900 py-2">{{ $relation->teacherSubjectRelation->subject->name ?? 'Tidak ada data' }}</td>
                    </tr>
                    <tr>
                        <td class="font-medium text-gray-700 w-1/4 py-2">Catatan</td>
                        <td class="text-gray-900 py-2">:</td>
                        <td class="text-gray-900 py-2">{{ $relation->note ?? 'Tidak ada catatan' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endif
