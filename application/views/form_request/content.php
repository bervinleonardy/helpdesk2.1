<div class="row">
    <div class="col-12">
        <div class="card m-b-30">
            <?= form_open('permintaan/simpandata', ['class' => 'formsimpan']); ?>
            <div class="pesan" style="display: none;"></div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label for="tanggal" class="col-sm-3 col-form-label">Tanggal</label>
                        <div class="col-sm-4">
                            <input class="form-control" type="text" id="tanggal" name="tanggal" readonly value="<?= date('d F y'); ?>">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label for="department" class="col-sm-12 col-form-label">No. Proyek/Departemen
                            <label class="text-danger">
                                <h4>*</h4>
                            </label>
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" id="department" name="department">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-7">
                            <input class="form-control" type="text" id="nama" name="nama" value="<?= $nama; ?>" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label for="lokasi" class="col-sm-5 col-form-label">Lokasi (Site/Bld/FI)
                            <label class="text-danger">
                                <h4>*</h4>
                            </label>
                        </label>
                        <div class="col-sm-6">
                            <input class="form-control" type="text" id="lokasi" name="lokasi">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label for="nik" class="col-sm-5 col-form-label">ID Karyawan</label>
                        <div class="col-sm-4">
                            <input class="form-control" type="text" id="nik" name="nik" value="<?= $id; ?>" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label for="statEmp" class="col-sm-5 col-form-label">Employee Status
                            <label class="text-danger">
                                <h4>*</h4>
                            </label>
                        </label>
                        <div class="col-sm-4">
                            <input class="form-control" type="text" id="statEmp" name="statEmp">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label for="position" class="col-sm-2 col-form-label">Posisi
                            <label class="text-danger">
                                <h4>*</h4>
                            </label>
                        </label>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" id="position" name="position">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label for="noAset" class="col-sm-3 col-form-label">No. Aset</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" id="noAset" name="noAset">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label for="ditujukanKe" class="col-sm-5 col-form-label">Ditujukan ke
                            <label class="text-danger">
                                <h4>*</h4>
                            </label>
                        </label>
                        <div class="col-sm-5">
                            <input class="form-control" type="text" id="ditujukanKe" name="ditujukanKe">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label for="tglButuh" class="col-sm-5 col-form-label">Tanggal Dibutuhkan
                            <label class="text-danger">
                                <h4>*</h4>
                            </label>
                        </label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="dd/mm/yyyy" id="tglDibutuhkan" name="tglDibutuhkan">
                                <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label for="department" class="col-sm-12 col-form-label"><label class="text-danger">
                                <h4>(*)</h4>
                            </label> Wajib Diisi !</label>
                    </div>
                </div>
                <h4 class="mt-0 header-title" style="text-align: center;">Status Permintaan</h4>
                <div class="general-label">
                    <div class="form-group row">
                        <label class="col-md-2 my-2 control-label">Kategori Permintaan
                            <label class="text-danger">
                                <h4>*</h4>
                            </label>
                        </label>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="kritikal" name="statReq" value="kritikal">
                                            <label class="custom-control-label" for="kritikal">Kritikal</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="tinggi" name="statReq" value="tinggi">
                                            <label class="custom-control-label" for="tinggi">Tinggi</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="normal" name="statReq" value="normal">
                                            <label class="custom-control-label" for="normal">Normal</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end row-->
                </div>
                <hr>
                <h4 class="mt-0 header-title" style="text-align: center;">Jenis Dukungan yang dibutuhkan</h4>
                <div class="general-label">
                    <div class="form-group row">
                        <label class="col-md-2 my-2 control-label">Akun User</label>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="baru" name="akunUser" value="baru">
                                            <label class="custom-control-label" for="baru">Baru</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="pemindahan" name="akunUser" value="pemindahan">
                                            <label class="custom-control-label" for="pemindahan">Pemindahan</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="penghentian" name="penghentian">
                                            <label class="custom-control-label" for="penghentian">Penghentian</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 my-2 control-label">Detail Aset</label>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="pemindahanAset" name="detailAset" value="pemindahan aset">
                                            <label class="custom-control-label" for="pemindahanAset">Pemindahan Aset</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="perbaikanAset" name="detailAset" value="perbaikan aset">
                                            <label class="custom-control-label" for="perbaikanAset">Perbaikan Aset</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkLainnyaDetailAset" name="detailAset" value="lainnya">
                                            <label class="custom-control-label" for="checkLainnyaDetailAset">Lainnya</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" id="lainnyaDetailAset" name="lainnyaDetailAset" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 my-2 control-label">Detail Peralatan</label>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="desktop" name="detailPeralatan[]" value="dekstop">
                                            <label class="custom-control-label" for="desktop">Desktop</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="workstation" name="detailPeralatan[]" value="workstation">
                                            <label class="custom-control-label" for="workstation">Workstation</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="laptop" name="detailPeralatan[]" value="laptop">
                                            <label class="custom-control-label" for="laptop">Laptop</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="blackberry" name="detailPeralatan[]" value="blackberry">
                                            <label class="custom-control-label" for="blackberry">Blackberry</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="iphone" name="detailPeralatan[]" value="iphone">
                                            <label class="custom-control-label" for="iphone">Iphone</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkLainnyadetailPeralatan" name="detailPeralatan[]" value="lainnya">
                                            <label class="custom-control-label" for="checkLainnyadetailPeralatan">Lainnya</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" id="lainnyaDetailPeralatan" name="lainnyaDetailPeralatan" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 my-2 control-label">Justifikasi Bisnis</label>
                        <div class="col-md-10">
                            <textarea id="justifikasiBisnis" name="justifikasiBisnis" class="form-control" maxlength="500" rows="4"></textarea>
                        </div>
                    </div>
                </div>
                <hr>
                <h4 class="mt-0 header-title" style="text-align: center;">Sistem dan Aplikasi</h4>
                <div class="general-label">
                    <div class="form-group row">
                        <label class="col-md-2 my-2 control-label">Softwares</label>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="sap" name="softwares[]" value="sap">
                                            <label class="custom-control-label" for="sap">SAP</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="hris" name="softwares[]" value="hris">
                                            <label class="custom-control-label" for="hris">HRIS</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkLainnyaSofware1" name="softwares[]" value="lainnya1">
                                            <label class="custom-control-label" for="checkLainnyaSofware1">
                                                <input class="form-control" type="text" id="lainnyaSoftwares1" name="lainnyaSoftwares1" disabled>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="mas" name="softwares[]" value="mas">
                                            <label class="custom-control-label" for="mas">MAS</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkLainnyaSofware2" name="softwares[]" value="lainnya2">
                                            <label class="custom-control-label" for="checkLainnyaSofware2">
                                                <input class="form-control" type="text" id="lainnyaSoftwares2" name="lainnyaSoftwares2" disabled>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkLainnyaSofware3" name="softwares[]" value="lainnya3">
                                            <label class="custom-control-label" for="checkLainnyaSofware3">
                                                <input class="form-control" type="text" id="lainnyaSoftwares3" name="lainnyaSoftwares3" disabled>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="visio" name="softwares[]" value="visio">
                                            <label class="custom-control-label" for="visio">MS. Visio</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkLainnyaSofware4" name="softwares[]" value="lainnya4">
                                            <label class="custom-control-label" for="checkLainnyaSofware4">
                                                <input class="form-control" type="text" id="lainnyaSoftwares4" name="lainnyaSoftwares4" disabled>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkLainnyaSofware5" name="softwares[]" value="lainnya5">
                                            <label class="custom-control-label" for="checkLainnyaSofware5">
                                                <input class="form-control" type="text" id="lainnyaSoftwares5" name="lainnyaSoftwares5" disabled>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="project" name="softwares[]" value="Ms. Projects">
                                            <label class="custom-control-label" for="project">MS. Projects</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkLainnyaSofware6" name="softwares[]" value="lainnya6">
                                            <label class="custom-control-label" for="checkLainnyaSofware6">
                                                <input class="form-control" type="text" id="lainnyaSoftwares6" name="lainnyaSoftwares6" disabled>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkLainnyaSofware7" name="softwares[]" value="lainnya7">
                                            <label class="custom-control-label" for="checkLainnyaSofware7">
                                                <input class="form-control" type="text" id="lainnyaSoftwares7" name="lainnyaSoftwares7" disabled>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="emailPerusahaan" name="softwares[]" value="email perusahaan">
                                            <label class="custom-control-label" for="emailPerusahaan">Email Perusahaan</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="emailPribadi" name="softwares[]" value="email pribadi">
                                            <label class="custom-control-label" for="emailPribadi">Email Eksternal/Pribadi</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="aksesInternet" name="softwares[]" value="akses internet">
                                            <label class="custom-control-label" for="aksesInternet">Akses Internet</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 my-2 control-label">Printer/Scanner/Lainnya</label>
                        <div class="col-md-10">
                            <textarea id="textarea" class="form-control" maxlength="500" rows="4"></textarea>
                        </div>
                    </div>
                </div>
                <hr>
                <h4 class="mt-0 header-title" style="text-align: center;">Akses Jaringan dan Akses Telepon</h4>
                <div class="general-label">
                    <div class="form-group row">
                        <label class="col-md-2 my-2 control-label">Koneksi Jaringan</label>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="lan" name="koneksiJaringan[]" value="lan">
                                            <label class="custom-control-label" for="lan">LAN</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="wifi" name="koneksiJaringan[]" value="wifi">
                                            <label class="custom-control-label" for="wifi">WIFI</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="vpn" name="koneksiJaringan[]" value="vpn">
                                            <label class="custom-control-label" for="vpn">VPN</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 my-2 control-label">Folder Sharing</label>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="projectFolder" name="folderSharing" value="project folder">
                                            <label class="custom-control-label" for="projectFolder">Project Folder</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="homeFolder" name="folderSharing" value="home folder">
                                            <label class="custom-control-label" for="homeFolder">Home Folder</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="sharingFolder" name="folderSharing" value="sharing folder">
                                            <label class="custom-control-label" for="sharingFolder">Sharing Folder</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 my-2 control-label">File Path</label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" id="filePath" name="filePath" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 my-2 control-label">Tipe Akses</label>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="fullAccess" name="tipeAkses[]" value="full access">
                                            <label class="custom-control-label" for="fullAccess">Full Access</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="readOnly" name="tipeAkses[]" value="read only">
                                            <label class="custom-control-label" for="readOnly">Read Only</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 my-2 control-label">Akses Telepon</label>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="viaOperator" name="aksesTelepon[]" value="via operator">
                                            <label class="custom-control-label" for="viaOperator">Via Operator</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="local" name="aksesTelepon[]" value="local">
                                            <label class="custom-control-label" for="local">Local</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="interlocal" name="aksesTelepon[]" value="interlocal">
                                            <label class="custom-control-label" for="interlocal">Interlocal/HP</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check-inline my-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="panggilanInternasional" name="aksesTelepon[]" value="panggilan internasional">
                                            <label class="custom-control-label" for="panggilanInternasional">Panggilan Internasional</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 my-2 control-label">Lainnya</label>
                        <div class="col-md-10">
                            <textarea id="lainnyaAksesJarTelp" name="lainnyaAksesJarTelp" class="form-control" maxlength="500" rows="4"></textarea>
                        </div>
                    </div>
                </div>
                <hr>
                <h4 class="mt-0 header-title" style="text-align: center;">Informasi Lainnya
                    <label class="text-danger">
                        <h4>*</h4>
                    </label>
                </h4>
                <div class="general-label">
                    <div class="form-group row">
                        <textarea id="informasiLainnya" name="informasiLainnya" class="form-control" maxlength="500" rows="4"></textarea>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="button-list float-right mb-3">
                    <button type="button" class="btn btn-secondary btn-lg" id="btnReset" name="btnReset" onclick=reset()>Reset</button>
                    <button type="submit" class="btn btn-lg btn-primary" id="btnSubmit" name="btnSubmit">Submit</button>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>