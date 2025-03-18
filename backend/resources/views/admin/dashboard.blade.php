<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $agentCount ?? '0' }}</h3>
                <p>Tổng số đại lý</p>
            </div>
            <div class="icon">
                <i class="fas fa-store"></i>
            </div>
            <a href="{{ route('admin.agents.index') }}" class="small-box-footer">Xem chi tiết <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>QR</h3>
                <p>Quản lý mã QR</p>
            </div>
            <div class="icon">
                <i class="fas fa-qrcode"></i>
            </div>
            <a href="{{ route('admin.agents.print-qr-codes') }}" class="small-box-footer">In mã QR <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>Barcode</h3>
                <p>Quản lý mã Barcode</p>
            </div>
            <div class="icon">
                <i class="fas fa-barcode"></i>
            </div>
            <a href="{{ route('admin.agents.index') }}" class="small-box-footer">Chọn đại lý <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <!-- ... rest of the code ... -->
</div>
