<div class="main-panel">
	<div class="content">
		<div class="panel-header bg-primary-gradient">
			<div class="page-inner py-5">
				<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
					<div>
						<h2 class="text-white pb-2 fw-bold">Payroll <small><?= config_item('version') ?> (Web-based App)</small></h2>
						<h5 class="text-white op-7 mb-0">Rumah Sakit Panti Nirmala</h5>
					</div>
				</div>
			</div>
		</div>
		<div class="page-inner mt--5 pb-0">
			<div class="row mt--2">
				<div class="col-md-12">
					<div class="row form-group">
						<div class="col-md-2 col-4 pl-1">
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text" id="btn-calendar"><i class="fa fa-calendar"></i></span>
								</div>
								<input id="tanggal" type="text" class="form-control text-center" placeholder="Tanggal" aria-label="Tanggal" aria-describedby="btn-calendar" data-toggle="datetimepicker" data-target="#tanggal" value="">
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 pr-0">
					<div class="card card-primary mb-2">
						<div class="card-header">
							<div class="card-title">Gaji</div>
							<div class="card-category">Periode <span id="p_gaji"></span></div>
						</div>
						<div class="card-body py-0">
							<div class="mb-4 mt-2">
								<h2 class="text-right"><span class="float-left">Gaji Bruto</span><small>Rp.</small><span id="n_gaji">0</span></h2>
								<h4 class="text-right text-warning"><span class="float-left">Potongan Gaji</span><small>- Rp.</small><span id="n_potongan">0</span></h4>
								<h1 class="text-right fw-bold"><small>Rp.</small><span id="n_diterima">0</span></h1>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 pl-2 pr-0">
					<div class="card card-secondary mb-2">
						<div class="card-header">
							<div class="card-title">Pegawai</div>
							<div class="card-category">Pegawai yang menerima gaji pada periode tersebut</div>
						</div>
						<div class="card-body py-0">
							<div class="mb-4 mt-2">
								<h2 class="text-right"><span class="float-left text-success text-sm">Tunai : <span id="tp_tunai">0</span> <small>Orang</small></span> <i class="far fa-credit-card"></i> <span id="tp_bank">0</span> <small>Orang</small></h2>
								<h4 class="text-right text-warning"><span class="float-left">PTT</span><span id="tp_ptt">0</span> <small>Orang</small></h4>
								<h1 class="text-right fw-bold"><span id="tp_peg">0</span> <small>Orang</small></h1>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 pl-2">
					<div class="card mb-2">
						<div class="card-body py-0">
							<div class="mb-4 mt-2">
								<h1 class="text-right fw-bold"><span class="float-left">Pegawai <small class="text-success">(realtime)</small></span><?= $peg['tk_tetap'] + $peg['tk_kontrak'] ?> <small>Orang</small></h1>
								<div class="separator-dashed mt-2"></div>
								<h4 class="text-right text-primary"><span class="float-left">Pegawai Tetap</span><?= $peg['tk_tetap'] - $peg['tk_ptt'] ?> <small>Orang</small></h4>
								<h4 class="text-right text-secondary"><span class="float-left">Kontrak</span><?= $peg['tk_kontrak'] ?> <small>Orang</small></h4>
								<h4 class="text-right text-warning"><span class="float-left">PTT</span><?= $peg['tk_ptt'] ?> <small>Orang</small></h4>
								<div class="separator-dashed"></div>
								<h4 class="text-right text-danger"><span class="float-left">Non-Aktif</span><?= $peg['tk_na'] ?> <small>Orang</small></h4>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 pr-2">
					<div class="card mb-2">
						<div class="card-header">
							<div class="card-title">Estimasi Gaji Tahun depan</div>
						</div>
						<div class="card-body">
							<form id="form-data">
								<div class="row form-group">
									<div class="input-group mb-3 col-md-3 pl-0">
										<div class="input-group-prepend">
											<span class="input-group-text bg-white"><i class="fa fa-angle-up text-success"></i></span>
										</div>
										<input type="text" id="kenaikan" class="form-control" value="" autocomplete="off">
										<div class="input-group-append">
											<span class="input-group-text">%</span>
										</div>
									</div>
									<div class="col-md-6">
										<button type="submit" id="btn-hitung" class="btn btn-primary"><i class="fa fa-calculator"></i> Hitung</button>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div id="perhitungan" class="text-right">&nbsp;</div>
										<h1 class="text-right"><small>Rp</small>&nbsp;<span id="estimasi">-</span></h1>
									</div>
								</div>
							</form>
							<small>
								<span class="text-danger">*)&nbsp;</span><span>Merupakan perhitungan dalam 1 tahun, dengan asumsi pegawai menempati jabatan yg sama tanpa memperhitungkan kenaikan golongan</span>
							</small>
						</div>
					</div>
					<div class="card">
						<div class="card-header">
							<div class="card-title">Perubahan Gaji berkala/Kenaikan golongan</div>
						</div>
						<div class="card-body px-1">
							<div class="table-responsive table-hover">
								<table id="kgb" class="table">
									<tbody></tbody>
								</table>
							</div>
						</div>
						<div class="card-footer mb-0">
							<ul class="0-legend html-legend">
								<li><span class="bg-info"></span>Berkala</li>
								<li><span class="bg-success"></span>Golongan</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-md-6 pl-0">
					<div class="row">
						<div class="col-md-6 pr-2">
							<div class="card mb-2">
								<div class="card-body">
									<div class="card-title fw-mediumbold">Akhir masa Kontrak</div>
									<div id="kontrak_expired" class="card-list"></div>
								</div>
							</div>
						</div>
						<div class="col-md-6 pl-0">
							<div class="card mb-2">
								<div class="card-header">
									<div class="card-title">Masa kerja > 16 tahun</div>
								</div>
								<div id="mk_16" class="card-body pb-0"></div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="card full-height">
								<div class="card-header">
									<div class="card-head-row">
										<div class="card-title">Pegawai Kontrak</div>
										<div class="card-tools">
											<a href="#" class="btn btn-info btn-border btn-round btn-sm mr-2">
												<span class="btn-label">
													<i class="fa fa-users"></i>
												</span>
												<span id="t_kontrak">-</span> Pegawai
											</a>
										</div>
									</div>
								</div>
								<div class="card-body">
									<table id="kontrak" class="table">
										<tbody></tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function() {
			$('#tanggal').datetimepicker({
				viewMode: 'months',
				format: 'MM/YYYY',
				autoclose: true,
				defaultDate: new Date()
			});
			$('#tanggal').on("change.datetimepicker", function() {
				reloadData();
			});
			$('#btn-calendar').click(function() {
				$('#tanggal').datetimepicker('show');
			});
			reloadData();
			$('#form-data').submit(function(e) {
				e.preventDefault();
				var b = $('#btn-hitung'),
					i = b.find('i'),
					cls = i.attr('class');
				$.ajax({
					type: 'POST',
					url: '<?= base_url('Dashboard/hitung_estimasi') ?>/' + $('#tanggal').val() + '/' + $('#kenaikan').val(),
					dataType: 'JSON',
					beforeSend: function() {
						i.removeClass().addClass('fa fa-spin fa-spinner');
					},
					success: function(r) {
						var gaji = parseInt(r.gaji);
						var tgaji = gaji * 12;
						$('#perhitungan').html('<b>' + gaji.formatMoney(0, '.', ',') + '</b> x 12bulan');
						$('#estimasi').html(tgaji.formatMoney(0, '.', ','));
						i.removeClass().addClass(cls);
					},
					error: function(e) {
						sweetMsg('error', 'Terjadi kesalahan!');
						i.removeClass().addClass(cls);
					}
				});
			});
		});

		function reloadData() {
			if ($('#tanggal').val() == '') {
				return true;
			}
			var b = $('#btn-calendar'),
				i = b.find('i'),
				cls = i.attr('class');
			$.ajax({
				type: 'POST',
				url: '<?= base_url('Dashboard/content') ?>/' + $('#tanggal').val(),
				dataType: 'JSON',
				beforeSend: function() {
					i.removeClass().addClass('fa fa-spin fa-spinner');
				},
				success: function(r) {
					var tnetto = parseInt(r.gaji['tnetto']);
					var tselisih = parseInt(r.gaji['tselisih']);
					var potongan = tnetto - tselisih;
					$('#p_gaji').html(r.periode);
					$('#n_gaji').html(tnetto.formatMoney(0, '.', ','));
					$('#n_potongan').html(potongan.formatMoney(0, '.', ','));
					$('#n_diterima').html(tselisih.formatMoney(0, '.', ','));

					var tk = parseInt(r.gaji['tk']);
					var tk_tunai = parseInt(r.gaji['tk_tunai']);
					$('#tp_peg').html(tk);
					$('#tp_tunai').html(tk_tunai);
					$('#tp_bank').html(tk - tk_tunai);
					$('#tp_ptt').html(r.gaji['tk_ptt']);

					// $('#kontrak').html(r.kontrak);
					setDataTableKontrak('#kontrak', r.kontrak);
					$('#t_kontrak').html(r.t_kontrak);
					$('#kontrak_expired').html(r.kontrak_expired);
					setDataTable('#kgb', r.kgb);
					$('#mk_16').html(r.mk_16);
					i.removeClass().addClass(cls);
				},
				error: function(e) {
					sweetMsg('error', 'Terjadi kesalahan!');
					i.removeClass().addClass(cls);
				}
			});
		}

		function setDataTable(a, tbody = '') {
			if ($.fn.DataTable.isDataTable(a)) {
				$(a).dataTable().fnDestroy();
			}
			$(a).find('tbody').html(tbody);
			$(a).DataTable({
				"responsive": true,
				"autoWidth": false,
				"scrollCollapse": true,
				"paging": true,
				"pageLength": 15,
				"order": [
					[2, 'desc']
				],
				"columnDefs": [{
					"targets": [0],
					"orderable": false,
				}, {
					"targets": [1],
				}, {
					"targets": [2],
					"className": "text-center",
				}, {
					"targets": [3],
					"className": "text-right",
					"orderable": false,
				}],
				"initComplete": function() {
					$('[data-toggle="tooltip"]').tooltip();
				}
			});
		}

		function setDataTableKontrak(a, tbody = '') {
			if ($.fn.DataTable.isDataTable(a)) {
				$(a).dataTable().fnDestroy();
			}
			$(a).find('tbody').html(tbody);
			$(a).DataTable({
				"responsive": true,
				"autoWidth": false,
				"scrollCollapse": true,
				"paging": true,
				"pageLength": 15,
				"order": [
					[2, 'desc']
				],
				"columnDefs": [{
					"targets": [0],
					"orderable": false,
				}, {
					"targets": [1],
				}, {
					"targets": [2],
					"className": "text-right",
					"orderable": false,
				}],
				"initComplete": function() {
					$('[data-toggle="tooltip"]').tooltip();
				}
			});
		}

		Number.prototype.formatMoney = function(c, d, t) {
			var n = this,
				c = isNaN(c = Math.abs(c)) ? 2 : c,
				d = d == undefined ? "." : d,
				t = t == undefined ? "," : t,
				s = n < 0 ? "-" : "",
				i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
				j = (j = i.length) > 3 ? j % 3 : 0;
			return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
		};
	</script>