<div class="modal-body">
    <form action="{{ route('schedule.update', $scheduleData->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="text" name="nama" value="{{ $scheduleData->sch_name }}">
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>