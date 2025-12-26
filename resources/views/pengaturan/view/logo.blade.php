<style>
    .upload-box {
        position: relative;
        width: 100%;
        height: 220px;
        border: 2px dashed #ccc;
        border-radius: 10px;
        cursor: pointer;
        overflow: hidden;
        background-color: #f8f9fa;
    }
    .upload-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        background-color: #fff;
    }
    .upload-box .overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 16px;
        opacity: 0;
        transition: 0.3s;
    }
    .upload-box:hover .overlay {
        opacity: 1;
    }
</style>
<div class="card-body">
    <form action="/app/pengaturan/logo/{{ $profil->id }}"
      id="FormLogo"
      method="post"
      enctype="multipart/form-data">
    @csrf

    <div class="row">
        <div class="col-md-5 mx-auto">
            <input type="file" id="logoInput"  name="logo" accept="image/*" hidden onchange="previewImage(this)">
            <div class="upload-box" onclick="document.getElementById('logoInput').click()">
                <img id="previewLogo" src="{{ asset('storage/' . $profil->logo) }}" alt="Logo">
                <div class="overlay">
                    <span>📷 Klik untuk upload</span>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="text-end mt-3 mt-md-10">
                <button class="btn btn-info" type="submit" id="SimpanLogo">
                    Simpan Logo
                </button>
            </div>
        </div>
    </div>
</form>

</div>
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('previewLogo').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
