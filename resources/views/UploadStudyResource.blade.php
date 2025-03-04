<!-- Upload Study Resource Modal -->
<div id="uploadStudyResourceModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/3 relative">
        <!-- Close Button -->
        <button onclick="closeModal()" class="absolute top-3 right-3 text-red-500 text-xl font-bold">×</button>

        <h2 class="text-2xl font-bold text-green-700 text-center mb-4">Upload Study Resource</h2>

        <form action="{{ route('study-resource.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="font-semibold text-gray-700">Course:</label>
                <span class="text-gray-600">Device Application</span>
            </div>

            <div class="mb-3">
                <label class="font-semibold text-gray-700">Chapter:</label>
                <span class="text-gray-600">{{ $chapter->chapterName }}</span>
            </div>

            <div class="mb-3">
                <label class="font-semibold text-gray-700">Part:</label>
                <select name="partID" class="w-full p-2 border border-gray-300 rounded">
                    @foreach ($parts as $part)
                        <option value="{{ $part->partID }}">{{ $part->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="font-semibold text-gray-700">Title:</label>
                <input type="text" name="title" class="w-full p-2 border border-gray-300 rounded" placeholder="Enter title" required>
            </div>

            <div class="mb-3">
                <label class="font-semibold text-gray-700">Lecture Notes (Optional):</label>
                <input type="file" name="lectureNotes" class="w-full p-2 border border-gray-300 rounded" accept=".pdf,.doc,.docx,.bmp,.png,.jpg,.jpeg" />
                <p class="text-xs text-gray-500 mt-1">
                    Please note that the maximum file size is **1GB**. Accepted formats: PDF, DOC, DOCX, BMP, PNG, JPG, JPEG.
                </p>
            </div>

            <div class="mb-3">
                <label class="font-semibold text-gray-700">Lecture Video (Optional):</label>
                <input type="file" name="lectureVideo" class="w-full p-2 border border-gray-300 rounded" accept=".avi,.mp4" />
                <p class="text-xs text-gray-500 mt-1">
                    Accepted formats: AVI, MP4.
                </p>
            </div>

            <div class="flex justify-between mt-5">
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded font-bold hover:bg-green-700">Confirm</button>
                <button type="button" onclick="closeModal()" class="border-2 border-green-600 text-green-600 px-6 py-2 rounded font-bold hover:bg-green-600 hover:text-white">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript for Modal -->
<script>
    function openModal() {
        document.getElementById('uploadStudyResourceModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('uploadStudyResourceModal').classList.add('hidden');
    }
</script>
