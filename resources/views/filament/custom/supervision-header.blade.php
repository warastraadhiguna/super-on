@if ($supervision)
    <div class="bg-gray-100 p-4 rounded-md mb-4">
        <h2 class="text-lg font-semibold text-gray-700 mb-2">📝 Informasi Berkas Supervisi</h2>
        
        <div class="bg-white p-4 rounded-lg shadow">
            <table class="w-full">
                <tbody>
                    <tr>
                        <td class="font-medium text-gray-700 w-1/4 py-2">Nama</td>
                        <td class="text-gray-900 py-2">:</td>
                        <td class="text-gray-900 py-2">{{ $supervision->name }}</td>
                    </tr>
                    <tr>
                        <td class="font-medium text-gray-700 w-1/4 py-2">Catatan</td>
                        <td class="text-gray-900 py-2">:</td>
                        <td class="text-gray-900 py-2">{{ $supervision->note ?? 'Tidak ada catatan' }}</td>
                    </tr>
                    <tr>
                        <td class="font-medium text-gray-700 w-1/4 py-2">Link</td>
                        <td class="text-gray-900 py-2">:</td>
                        <td class="text-gray-900 py-2">
                            @if ($supervision->link)
                                <a href="{{ $supervision->link }}" target="_blank" class="text-blue-500 underline">
                                    Buka Link 🔗
                                </a>
                            @else
                                <span class="text-gray-500">Tidak ada link</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="font-medium text-gray-700 w-1/4 py-2">Dokumen</td>
                        <td class="text-gray-900 py-2">:</td>
                        <td class="text-gray-900 py-2">
                            @if ($supervision->document)
                                <a href="{{ asset('storage/' . $supervision->document) }}" target="_blank" 
                                    class="text-green-500 underline">
                                    Unduh Dokumen 📄
                                </a>
                            @else
                                <span class="text-gray-500">Tidak ada dokumen</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endif
