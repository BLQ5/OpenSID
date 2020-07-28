<script type="text/javascript">
	var chart;

	function grafikType() {
		chart = new Highcharts.Chart({
			chart: {
				renderTo: 'chart',
				defaultSeriesType: 'column'
			},
			title: 0,
			xAxis: {
				title: {
					text: '<?= $stat?>'
				},
				categories: [
				<?php $i=0; foreach ($main as $data): $i++;?>
				<?php if ($data['jumlah'] != "-"): ?><?= "'$i',";?><?php endif; ?>
			<?php endforeach;?>
			]
		},
		yAxis: {
			title: {
				text: 'Jumlah Populasi'
			}
		},
		legend: {
			layout: 'vertical',
			enabled: false
		},
		plotOptions: {
			series: {
				colorByPoint: true
			},
			column: {
				pointPadding: 0,
				borderWidth: 0
			}
		},
		series: [{
			shadow:1,
			border:1,
			data: [
			<?php foreach ($main as $data): ?>
				<?php if (!in_array($data['nama'], array("TOTAL", "JUMLAH", "PENERIMA"))): ?>
					<?php if ($data['jumlah'] != "-"): ?>
						['<?= strtoupper($data['nama'])?>',<?= $data['jumlah']?>],
					<?php endif; ?>
				<?php endif; ?>
				<?php endforeach;?>]
			}]
		});
	}

	function pieType() {
		chart = new Highcharts.Chart({
			chart: {
				renderTo: 'chart',
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false
			},
			title: 0,
			plotOptions: {
				index: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels:
					{
						enabled: true
					},
					showInLegend: true
				}
			},
			legend: {
				layout: 'vertical',
				backgroundColor: '#FFFFFF',
				align: 'right',
				verticalAlign: 'top',
				x: -30,
				y: 0,
				floating: true,
				shadow: true,
				enabled:true
			},
			series: [{
				type: 'pie',
				name: 'Populasi',
				data: [
				<?php foreach ($main as $data): ?>
					<?php if (!in_array($data['nama'], array("TOTAL", "JUMLAH", "PENERIMA"))): ?>
						<?php if ($data['jumlah'] != "-"): ?>
							["<?= strtoupper($data['nama'])?>",<?= $data['jumlah']?>],
						<?php endif; ?>
					<?php endif; ?>
				<?php endforeach;?>
				]
			}]
		});
	}
</script>
<style type="text/css">
	.disabled {
		pointer-events: none;
		cursor: default;
	}

	.table, th {
		text-align: center;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Statistik Kependudukan</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Statistik Kependudukan <?= $dusun; ?></li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="row">
				<div class="col-md-4">
					<?php $this->load->view('statistik/side_menu.php')?>
				</div>
				<div class="col-md-8">
					<div class="box box-info">
						<div class="box-header with-border">
							<a href="<?=site_url("statistik/daftar/cetak")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Laporan" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Laporan"><i class="fa fa-print "></i>Cetak
							</a>
							<a href="<?=site_url("statistik/daftar/unduh")?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Laporan" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Laporan"><i class="fa fa-print "></i>Unduh
							</a>
							<a class="btn btn-social btn-flat bg-orange btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block grafikType" title="Grafik Data" id="grafikType" onclick="grafikType();">
								<i class="fa fa-bar-chart"></i>Grafik Data
							</a>
							<a class="btn btn-social btn-flat btn-primary btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block pieType" title="Pie Data" id="pieType" onclick="pieType();">
								<i class="fa fa-pie-chart"></i>Pie Data
							</a>
							<?php if ($lap=='13'): ?>
								<a href="<?=site_url("statistik/rentang_umur")?>" class="btn btn-social btn-flat bg-olive btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Rentang Umur">
									<i class="fa fa-arrows-h"></i>Rentang Umur
								</a>
							<?php endif; ?>
							<a href="<?= site_url("{$this->controller}/clear/$lap") ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i>Bersihkan Filter</a>
						</div>
						<div class="box-body">
							<div class="col-sm-12">
								<?php if ($lap < 50): ?>
									<h4 class="box-title text-center"><b>Data Kependudukan Menurut <?= ($stat);?></b></h4>
									<?php else: ?>
										<h4 class="box-title text-center"><b>Data Peserta Program <?= ($program['nama'])?></b></h4>
									<?php endif; ?>
									<div id="chart"></div>
									<hr>
									<?php if (($lap <= 20 OR $lap == 'bantuan_penduduk') AND $lap <> 'kelas_sosial' AND $lap <> 'bantuan_keluarga') : ?>
										<div class="row">
											<div class="col-sm-12 form-inline">
												<form action="" id="mainform" method="post">
													<select class="form-control input-sm " name="dusun" onchange="formAction('mainform','<?= site_url('statistik/dusun')?>')">
														<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun)?></option>
														<?php foreach ($list_dusun AS $data): ?>
															<option value="<?= $data['dusun']?>" <?= selected($dusun, $data['dusun']); ?>><?= set_ucwords($data['dusun'])?></option>
														<?php endforeach; ?>
													</select>
													<?php if ($dusun): ?>
														<select class="form-control input-sm" name="rw" onchange="formAction('mainform','<?= site_url('statistik/rw')?>')" >
															<option value="">Pilih RW</option>
															<?php foreach ($list_rw AS $data): ?>
																<option value="<?= $data['rw']?>" <?= selected($rw, $data['rw']); ?>><?= set_ucwords($data['rw'])?></option>
															<?php endforeach; ?>
														</select>
													<?php endif; ?>
													<?php if ($rw): ?>
														<select class="form-control input-sm" name="rt" onchange="formAction('mainform','<?= site_url('statistik/rt')?>')">
															<option value="">Pilih RT</option>
															<?php foreach ($list_rt AS $data): ?>
																<option value="<?= $data['rt']?>" <?= selected($rt, $data['rt']); ?>><?= set_ucwords($data['rt'])?></option>
															<?php endforeach; ?>
														</select>
													<?php endif; ?>
												</form>
											</div>
										</div>
									<?php endif ?>
									<div class="table-responsive">
										<table class="table table-bordered dataTable table-striped table-hover nowrap">
											<thead class="bg-gray color-palette">
												<tr>
													<th class="padat">No</th>
													<?php if ($order_by==2): ?>
														<th><a href="<?= site_url("statistik/order_by/$lap/1")?>"><?= $judul_kelompok ?> <i class='fa fa-sort-asc fa-sm'></i></a></th>
													<?php elseif ($order_by==1): ?>
														<th><a href="<?= site_url("statistik/order_by/$lap/2")?>"><?= $judul_kelompok ?> <i class='fa fa-sort-desc fa-sm'></i></a></th>
													<?php else: ?>
														<th><a href="<?= site_url("statistik/order_by/$lap/1")?>"><?= $judul_kelompok ?> <i class='fa fa-sort fa-sm'></i></a></th>
													<?php endif; ?>
													<?php if ($order_by==6): ?>
														<th nowrap colspan="2"><a href="<?= site_url("statistik/order_by/$lap/5")?>">Jumlah <i class='fa fa-sort-asc fa-sm'></i></a></th>
													<?php elseif ($order_by==5): ?>
														<th nowrap colspan="2"><a href="<?= site_url("statistik/order_by/$lap/6")?>">Jumlah <i class='fa fa-sort-desc fa-sm'></i></a></th>
													<?php else: ?>
														<th nowrap colspan="2"><a href="<?= site_url("statistik/order_by/$lap/5")?>">Jumlah <i class='fa fa-sort fa-sm'></i></a></th>
													<?php endif; ?>
													<?php if ($jenis_laporan == 'penduduk'): ?>
														<?php if ($order_by==4): ?>
															<th nowrap colspan="2"><a href="<?= site_url("statistik/order_by/$lap/3")?>">Laki-Laki <i class='fa fa-sort-asc fa-sm'></i></a></th>
														<?php elseif ($order_by==3): ?>
															<th nowrap colspan="2"><a href="<?= site_url("statistik/order_by/$lap/4")?>">Laki-Laki <i class='fa fa-sort-desc fa-sm'></i></a></th>
														<?php else: ?>
															<th nowrap colspan="2"><a href="<?= site_url("statistik/order_by/$lap/3")?>">Laki-Laki <i class='fa fa-sort fa-sm'></i></a></th>
														<?php endif; ?>
														<?php if ($order_by==8): ?>
															<th nowrap colspan="2"><a href="<?= site_url("statistik/order_by/$lap/7")?>">Perempuan <i class='fa fa-sort-asc fa-sm'></i></a></th>
														<?php elseif ($order_by==7): ?>
															<th nowrap colspan="2"><a href="<?= site_url("statistik/order_by/$lap/8")?>">Perempuan <i class='fa fa-sort-desc fa-sm'></i></a></th>
														<?php else: ?>
															<th nowrap colspan="2"><a href="<?= site_url("statistik/order_by/$lap/7")?>">Perempuan <i class='fa fa-sort fa-sm'></i></a></th>
														<?php endif; ?>
													<?php endif; ?>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($main as $data): ?>
													<?php if ($lap>50) $tautan_jumlah = site_url("program_bantuan/detail/$lap/1"); ?>
														<tr>
															<td class="text-center"><?= $data['no']?></td>
															<td class="text-left"><?= strtoupper($data['nama']);?></td>
															<td class="text-right">
																<?php if (in_array($lap, array(21, 22, 23, 24, 25, 26, 27, 'kelas_sosial', 'bantuan_keluarga'))): ?>
																	<a href="<?= site_url("keluarga/statistik/$lap/$data[id]")?>/0" <?= jecho($data['id'], 'JUMLAH', 'class="disabled"'); ?>><?= $data['jumlah']?></a>
																<?php else: ?>
																	<?php if ($lap<50) $tautan_jumlah = site_url("penduduk/statistik/$lap/$data[id]"); ?>
																		<a href="<?= $tautan_jumlah ?>/0" <?= jecho($data['id'], 'JUMLAH', 'class="disabled"'); ?>><?= $data['jumlah']?></a>
																	<?php endif; ?>
															</td>
															<td class="text-right"><?= $data['persen'];?></td>
																<?php if (in_array($lap, array(21, 22, 23, 24, 25, 26, 27, 'kelas_sosial', 'bantuan_keluarga'))):
																	$tautan_jumlah = site_url("keluarga/statistik/$lap/$data[id]");
																elseif ($lap<50): $tautan_jumlah = site_url("penduduk/statistik/$lap/$data[id]");endif;
																?>
																<?php if ($jenis_laporan == 'penduduk'): ?>
																	<td class="text-right"><a href="<?= $tautan_jumlah?>/1" <?= jecho($data['id'], 'JUMLAH', 'class="disabled"'); ?>><?= $data['laki']?></a></td>
															<td class="text-right"><?= $data['persen1'];?></td>
															<td class="text-right"><a href="<?= $tautan_jumlah?>/2" <?= jecho($data['id'], 'JUMLAH', 'class="disabled"'); ?>><?= $data['perempuan']?></a></td>
															<td class="text-right"><?= $data['persen2'];?></td>
														</tr>
													<?php endif; ?>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
									<?php if (in_array($lap, array('bantuan_keluarga', 'bantuan_penduduk'))):?>
										<p class="text-muted text-justify text-red"><b>Catatan:</b> "Pada jumlah PENERIMA, setiap peserta terhitung satu sekali saja, meskipun menerima lebih dari satu jenis bantuan".</p>
										<br><br>
									<?php endif; ?>
								</div>

							<?php if (in_array($lap, array('bantuan_keluarga', 'bantuan_penduduk'))):?>
								<?php $this->load->view('statistik/peserta_bantuan'); ?>
							<?php endif;?>

						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
