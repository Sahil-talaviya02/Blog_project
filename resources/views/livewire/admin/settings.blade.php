<div> {{-- ✅ SINGLE ROOT ELEMENT --}}

    <div class="tab">

        <ul class="nav nav-tabs customtab" role="tablist">

            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#general_settings">
                    General Settings
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#logo_favicon">
                    Logo & Favicon
                </a>
            </li>

        </ul>

        <div class="tab-content">

            {{-- ================= GENERAL SETTINGS ================= --}}
            <div class="tab-pane fade show active" id="general_settings">

                <div class="pd-20">

                    <form wire:submit="updateSiteInfo">
                        <div class="row">

                            <div class="col-md-6">
                                <label>Site Title</label>
                                <input type="text" name="site_title" class="form-control" value="{{ settings()->site_title ?? '' }}">
                                @error('site_title')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label>Site Email</label>
                                <input type="text" name="site_email" class="form-control" value="{{ settings()->site_email ?? '' }}">
                                @error('site_email')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <div class="mt-3">
                            <button class="btn btn-primary">Update</button>
                        </div>
                    </form>

                </div>

            </div>

            {{-- ================= LOGO & FAVICON ================= --}}
            <div class="tab-pane fade" id="logo_favicon">

                <div class="pd-20">

                    <div class="row">
                        <div class="col-md-6">

                            {{-- LOGO --}}
                            <h6>Site Logo</h6>
                            <div class="mb-2">
                                <img id="preview_site_logo"
                                    src="{{ settings()?->site_logo ? asset('images/logos/' . settings()->site_logo->logo) : '' }}"
                                    class="img-thumbnail" width="150">
                            </div>

                            {{-- FAVICON --}}
                            <h6 class="mt-3">Favicon</h6>
                            <div class="mb-2">
                                <img id="preview_favicon"
                                    src="{{ settings()?->site_favicon ? asset('images/logos/' . settings()->site_favicon) : '' }}"
                                    class="img-thumbnail" width="60">
                            </div>

                            {{-- FORM --}}
                            <form id="updateLogoForm" action="{{ route('admin.update_logo') }}" method="POST"
                                enctype="multipart/form-data">

                                @csrf

                                <div class="mb-2">
                                    <label>Upload Logo</label>
                                    <input type="file" name="site_logo" class="form-control">
                                </div>

                                <div class="mb-2">
                                    <label>Upload Favicon</label>
                                    <input type="file" name="site_favicon" class="form-control">
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    Update
                                </button>

                            </form>

                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
