@extends('layouts.app')

@section('content')
<div style="animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);">
    <!-- Page Header with Gradient -->
    <div style="margin-bottom: 2rem; padding: 2rem; background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%); border-radius: 18px; color: white;">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
            <i class="fas fa-file-upload" style="font-size: 2rem;"></i>
            <div>
                <h1 style="margin: 0; font-size: 2rem; font-weight: 700; font-family: 'Space Grotesk', sans-serif;">Upload Report</h1>
                <p style="margin: 0.5rem 0 0 0; opacity: 0.9; font-size: 0.95rem;">Share insights with PDF, Excel, CSV, Word & Images</p>
            </div>
        </div>
    </div>

    <!-- Main Form Container -->
    <div style="background: rgba(255, 255, 255, 0.94); border: 1px solid rgba(17, 24, 39, 0.08); border-radius: 18px; padding: 2rem; box-shadow: 0 10px 28px rgba(17, 24, 39, 0.04);">
        @if ($errors->any())
            <div style="background: #fee2e2; border: 1px solid #fecaca; border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 1.5rem; display: flex; gap: 1rem;">
                <i class="fas fa-circle-exclamation" style="color: #ef4444; margin-top: 0.25rem; font-size: 1.25rem;"></i>
                <div>
                    <h3 style="margin: 0 0 0.5rem 0; color: #991b1b; font-weight: 600;">Validation Error</h3>
                    <ul style="margin: 0; padding-left: 1.5rem; color: #b91c1c;">
                        @foreach ($errors->all() as $error)
                            <li style="margin: 0.3rem 0;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('reports.upload.store') }}" method="POST" enctype="multipart/form-data" style="display: grid; gap: 2rem;">
            @csrf

            <!-- Title Field -->
            <div>
                <label for="title" style="display: block; font-weight: 500; font-size: 0.9rem; color: var(--gray-700); margin-bottom: 0.5rem;">
                    <span style="color: #ef4444;">*</span> Report Title
                </label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    value="{{ old('title') }}"
                    placeholder="e.g., Q1 Market Analysis Report"
                    style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--gray-300); border-radius: 8px; font-size: 0.9rem; font-family: 'Manrope', sans-serif; transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);"
                    onfocus="this.style.borderColor='#0d9488'; this.style.boxShadow='0 0 0 4px rgba(13, 148, 136, 0.12)'; this.style.outline='none';"
                    onblur="this.style.borderColor='var(--gray-300)'; this.style.boxShadow='none';"
                    required
                >
                @error('title')
                    <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.35rem;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description Field -->
            <div>
                <label for="description" style="display: block; font-weight: 500; font-size: 0.9rem; color: var(--gray-700); margin-bottom: 0.5rem;">
                    Description <span style="color: #10b981; font-size: 0.8rem;">(Optional)</span>
                </label>
                <textarea 
                    id="description" 
                    name="description" 
                    rows="4"
                    placeholder="Describe the report content, key findings, or important notes..."
                    style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--gray-300); border-radius: 8px; font-size: 0.9rem; font-family: 'Manrope', sans-serif; resize: vertical; transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);"
                    onfocus="this.style.borderColor='#0d9488'; this.style.boxShadow='0 0 0 4px rgba(13, 148, 136, 0.12)'; this.style.outline='none';"
                    onblur="this.style.borderColor='var(--gray-300)'; this.style.boxShadow='none';"
                >{{ old('description') }}</textarea>
                @error('description')
                    <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.35rem;">{{ $message }}</p>
                @enderror
            </div>

            <!-- File Upload Section -->
            <div>
                <label for="file" style="display: block; font-weight: 500; font-size: 0.9rem; color: var(--gray-700); margin-bottom: 1rem;">
                    <span style="color: #ef4444;">*</span> Upload File
                </label>

                <!-- Drag & Drop Zone -->
                <div 
                    id="dropZone"
                    style="border: 2px dashed #cbd5e1; border-radius: 12px; padding: 2.5rem; text-align: center; cursor: pointer; transition: all 0.3s ease; background: linear-gradient(135deg, rgba(13, 148, 136, 0.02) 0%, rgba(5, 150, 213, 0.02) 100%);"
                    onmouseover="this.style.borderColor='#0d9488'; this.style.background='linear-gradient(135deg, rgba(13, 148, 136, 0.08) 0%, rgba(5, 150, 213, 0.08) 100%)';"
                    onmouseout="this.style.borderColor='#cbd5e1'; this.style.background='linear-gradient(135deg, rgba(13, 148, 136, 0.02) 0%, rgba(5, 150, 213, 0.02) 100%)';"
                >
                    <input 
                        type="file" 
                        id="file" 
                        name="file"
                        accept=".pdf,.xlsx,.xls,.csv,.docx,.jpg,.jpeg,.png"
                        style="display: none;"
                        required
                    >

                    <div style="pointer-events: none;">
                        <svg style="width: 3.5rem; height: 3.5rem; margin: 0 auto 1rem; color: #0d9488; opacity: 0.6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>

                        <p style="margin: 0 0 0.5rem 0; font-weight: 600; color: var(--gray-900); font-size: 1rem;">
                            <span style="color: #0d9488; cursor: pointer;" onclick="document.getElementById('file').click();">Click to upload</span>
                            <span style="color: var(--gray-500); font-weight: 400;"> or drag and drop</span>
                        </p>
                        <p style="margin: 0; font-size: 0.8rem; color: var(--gray-500);">PDF, Excel, CSV, Word, JPG, PNG • Max 20 MB</p>
                    </div>

                    <p id="fileName" style="margin: 1rem 0 0 0; font-size: 0.9rem; color: #10b981; font-weight: 600; display: none; animation: slideDown 0.3s ease;">
                        <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i><span id="fileNameText"></span>
                    </p>
                </div>

                @error('file')
                    <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.5rem;">{{ $message }}</p>
                @enderror
            </div>

            <!-- File Type Info Box -->
            <div style="background: linear-gradient(135deg, #e0f2fe 0%, #e0fdf4 100%); border: 1px solid #bfdbfe; border-radius: 12px; padding: 1.25rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 2.5rem; height: 2.5rem; background: #dbeafe; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #1e40af;">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div>
                        <p style="margin: 0; font-size: 0.75rem; color: #1e40af; font-weight: 600; text-transform: uppercase;">PDF</p>
                        <p style="margin: 0.2rem 0 0 0; font-size: 0.85rem; color: #0369a1;">Documents</p>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 2.5rem; height: 2.5rem; background: #dcfce7; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #15803d;">
                        <i class="fas fa-file-excel"></i>
                    </div>
                    <div>
                        <p style="margin: 0; font-size: 0.75rem; color: #15803d; font-weight: 600; text-transform: uppercase;">Excel</p>
                        <p style="margin: 0.2rem 0 0 0; font-size: 0.85rem; color: #16a34a;">Spreadsheets</p>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 2.5rem; height: 2.5rem; background: #fef3c7; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #92400e;">
                        <i class="fas fa-file-csv"></i>
                    </div>
                    <div>
                        <p style="margin: 0; font-size: 0.75rem; color: #92400e; font-weight: 600; text-transform: uppercase;">CSV</p>
                        <p style="margin: 0.2rem 0 0 0; font-size: 0.85rem; color: #b45309;">Data Files</p>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 2.5rem; height: 2.5rem; background: #f3e8ff; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #7e22ce;">
                        <i class="fas fa-file-word"></i>
                    </div>
                    <div>
                        <p style="margin: 0; font-size: 0.75rem; color: #7e22ce; font-weight: 600; text-transform: uppercase;">Word</p>
                        <p style="margin: 0.2rem 0 0 0; font-size: 0.85rem; color: #a855f7;">Documents</p>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 2.5rem; height: 2.5rem; background: #fee2e2; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #991b1b;">
                        <i class="fas fa-file-image"></i>
                    </div>
                    <div>
                        <p style="margin: 0; font-size: 0.75rem; color: #991b1b; font-weight: 600; text-transform: uppercase;">Images</p>
                        <p style="margin: 0.2rem 0 0 0; font-size: 0.85rem; color: #b91c1c;">JPG, PNG</p>
                    </div>
                </div>
            </div>

            <!-- Info Alert -->
            @if(!auth()->user()->isAdmin())
            <div style="background: #fef3c7; border: 1px solid #fde047; border-radius: 12px; padding: 1rem 1.5rem; display: flex; gap: 1rem; align-items: flex-start;">
                <i class="fas fa-info-circle" style="color: #92400e; font-size: 1.25rem; margin-top: 0.2rem;"></i>
                <div>
                    <p style="margin: 0; color: #92400e; font-size: 0.9rem; font-weight: 500;">
                        <strong>Heads up!</strong> Your report will be reviewed by our team before approval. Track the status in "My Uploads".
                    </p>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; padding-top: 1rem; border-top: 1px solid var(--gray-200);">
                <button 
                    type="submit" 
                    style="background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); font-family: 'Manrope', sans-serif; display: inline-flex; align-items: center; justify-content: center; gap: 0.75rem; width: 100%;"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 15px rgba(13, 148, 136, 0.3)';"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';"
                >
                    <i class="fas fa-cloud-arrow-up"></i> Upload Report
                </button>
                <a 
                    href="{{ route('reports.myUploads') }}" 
                    style="background: white; color: var(--gray-700); border: 1px solid var(--gray-300); padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 500; font-size: 0.95rem; cursor: pointer; transition: all 0.2s ease; font-family: 'Manrope', sans-serif; display: inline-flex; align-items: center; justify-content: center; gap: 0.75rem; text-decoration: none; width: 100%;"
                    onmouseover="this.style.borderColor='#0d9488'; this.style.color='#0d9488'; this.style.background='rgba(13, 148, 136, 0.04)';"
                    onmouseout="this.style.borderColor='var(--gray-300)'; this.style.color='var(--gray-700)'; this.style.background='white';"
                >
                    <i class="fas fa-arrow-left"></i> My Uploads
                </a>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

:root {
    --gray-100: #f4f1ea;
    --gray-200: #e7dfd1;
    --gray-300: #cbd5e1;
    --gray-500: #736b5c;
    --gray-700: #2f2c26;
    --gray-900: #1c1917;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('file');
    const fileName = document.getElementById('fileName');
    const fileNameText = document.getElementById('fileNameText');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    dropZone.addEventListener('drop', function(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        updateFileName();
    }, false);

    fileInput.addEventListener('change', updateFileName);

    function updateFileName() {
        if (fileInput.files && fileInput.files[0]) {
            fileNameText.textContent = fileInput.files[0].name;
            fileName.style.display = 'block';
        } else {
            fileName.style.display = 'none';
        }
    }

    dropZone.addEventListener('click', () => {
        fileInput.click();
    });
});
</script>
@endsection
