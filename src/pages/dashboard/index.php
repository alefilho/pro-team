<!-- Header -->
<div class="header pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 d-inline-block mb-0">Dashboard</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </nav>
        </div>
        <div class="col-lg-6 col-5 text-right">
          <!-- <a href="#" class="btn btn-sm btn-neutral">New</a>
          <a href="#" class="btn btn-sm btn-neutral">Filters</a> -->
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
  <!-- DASH POINTERS -->
  <div class="row">
    <div class="col-xl-3 col-md-6">
      <div class="card bg-gradient-primary border-0">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h5 class="card-title text-uppercase text-muted mb-0 text-white">MEMBROS</h5>
              <?php
              $Read->FullRead("SELECT
                	COUNT( mem_id ) AS count
                FROM
                	members
                	LEFT JOIN classes ON cla_id = mem_idclass
                WHERE
                	cla_iduser = {$_SESSION['userlogin']['use_id']}"
              );
              ?>
              <span class="h2 font-weight-bold mb-0 text-white"><?= $Read->getResult()[0]['count']; ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6">
      <div class="card bg-gradient-info border-0">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h5 class="card-title text-uppercase text-muted mb-0 text-white">CLASSES</h5>
              <?php
              $Read->FullRead("SELECT
                	COUNT( cla_id ) AS count
                FROM
                	classes
                WHERE
                	cla_iduser = {$_SESSION['userlogin']['use_id']}"
              );
              ?>
              <span class="h2 font-weight-bold mb-0 text-white"><?= $Read->getResult()[0]['count']; ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6">
      <div class="card bg-gradient-danger border-0">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h5 class="card-title text-uppercase text-muted mb-0 text-white">TIMES</h5>
              <?php
              $Read->FullRead("SELECT
                	tea_id,
                	tea_name,
                	tea_description,
                	tea_createdat,
                	tea_updatedat,
                  cla_name,
                	GROUP_CONCAT(mem_name ORDER BY mem_name) AS members
                FROM
                	teams
                	LEFT JOIN teams_members ON tme_idteam = tea_id
                	LEFT JOIN members ON mem_id = tme_idmember
                	LEFT JOIN classes ON cla_id = mem_idclass
                WHERE
                	cla_iduser = {$_SESSION['userlogin']['use_id']}
                GROUP BY
                	tea_id,
                	tea_name,
                	tea_description,
                	tea_createdat,
                	tea_updatedat,
                  cla_name"
              );
              ?>
              <span class="h2 font-weight-bold mb-0 text-white"><?= $Read->getRowCount(); ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6">
      <div class="card bg-gradient-default border-0">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h5 class="card-title text-uppercase text-muted mb-0 text-white">FEEDBACKS</h5>
              <?php
              $Read->FullRead("SELECT
                	COUNT( DISTINCT fee_id ) AS count
                FROM
                	sessions
                	LEFT JOIN classes ON cla_id = ses_idclass
                	LEFT JOIN sessions_topics ON top_idsession = ses_id
                	LEFT JOIN sessions_topics_feedbacks ON fee_idtopic = top_id
                WHERE
                	cla_iduser = {$_SESSION['userlogin']['use_id']}"
              );
              ?>
              <span class="h2 font-weight-bold mb-0 text-white"><?= $Read->getResult()[0]['count']; ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php
  $w = " AND fee_createdat >= '".date("Y-m-d", strtotime('-30 day', time()))." 00:00:00' AND fee_createdat <= '".date("Y-m-d")." 23:59:59' ";
  $Read->FullRead("SELECT
    	COUNT( fee_id ) AS count,
    	DATE_FORMAT( fee_createdat, '%Y-%m-%d' ) AS fee_createdat
    FROM
    	sessions_topics_feedbacks
    	LEFT JOIN sessions_topics ON top_id = fee_idtopic
    	LEFT JOIN sessions ON ses_id = top_idsession
    	LEFT JOIN classes ON cla_id = ses_idclass
    WHERE
    	cla_iduser = {$_SESSION['userlogin']['use_id']}
      {$w}
    GROUP BY
    	DATE_FORMAT( fee_createdat, '%Y-%m-%d' )
    ORDER BY
    	DATE_FORMAT( fee_createdat, '%Y-%m-%d' )"
  );

  $labels = [];
  $datasets = [];
  if ($Read->getResult()) {
    foreach ($Read->getResult() as $key => $value) {
      $labels[] = "'{$value['fee_createdat']}'";
      $datasets[] = $value['count'];
    }
  }

  $data = "{
    labels: [".implode(",", $labels)."],
    datasets: [{
      label: 'Cadastros',
      data: [".implode(",", $datasets)."]
    }]
  }";
  ?>

  <div class="card-deck flex-column flex-xl-row">
    <div class="card">
      <div class="card-header bg-transparent">
        <h6 class="text-muted text-uppercase ls-1 mb-1">Overview</h6>
        <h2 class="h3 mb-0">Feedbacks</h2>
      </div>
      <div class="card-body">
        <!-- Chart -->
        <div class="chart">
          <!-- Chart wrapper -->
          <canvas id="chart" class="chart-canvas"></canvas>
        </div>
      </div>
    </div>
    <!-- Progress track -->
  </div>

  <?php include 'src/components/footer.php'; ?>
</div>

<script>
'use strict';

var Charts = (function() {

	// Variable

	var $toggle = $('[data-toggle="chart"]');
	var mode = 'light';//(themeMode) ? themeMode : 'light';
	var fonts = {
		base: 'Open Sans'
	}

	// Colors
	var colors = {
		gray: {
			100: '#f6f9fc',
			200: '',
			300: '#dee2e6',
			400: '#ced4da',
			500: '#adb5bd',
			600: '#8898aa',
			700: '#525f7f',
			800: '#32325d',
			900: '#212529'
		},
		theme: {
			'default': '#172b4d',
			'primary': '#5e72e4',
			'secondary': '#f4f5f7',
			'info': '#11cdef',
			'success': '#2dce89',
			'danger': '#f5365c',
			'warning': '#fb6340'
		},
		black: '#12263F',
		white: '#FFFFFF',
		transparent: 'transparent',
	};


	// Methods

	// Chart.js global options
	function chartOptions() {

		// Options
		var options = {
			defaults: {
				global: {
					responsive: true,
					maintainAspectRatio: false,
					defaultColor: (mode == 'dark') ? colors.gray[700] : colors.gray[600],
					defaultFontColor: (mode == 'dark') ? colors.gray[700] : colors.gray[600],
					defaultFontFamily: fonts.base,
					defaultFontSize: 13,
					layout: {
						padding: 0
					},
					legend: {
						display: false,
						position: 'bottom',
						labels: {
							usePointStyle: true,
							padding: 16
						}
					},
					elements: {
						point: {
							radius: 0,
							backgroundColor: colors.theme['primary']
						},
						line: {
							tension: .4,
							borderWidth: 4,
							borderColor: colors.theme['primary'],
							backgroundColor: colors.transparent,
							borderCapStyle: 'rounded'
						},
						rectangle: {
							backgroundColor: colors.theme['warning']
						},
						arc: {
							backgroundColor: colors.theme['primary'],
							borderColor: (mode == 'dark') ? colors.gray[800] : colors.white,
							borderWidth: 4
						}
					},
					tooltips: {
						enabled: true,
						mode: 'index',
						intersect: false,
					}
				},
				doughnut: {
					cutoutPercentage: 83,
					legendCallback: function(chart) {
						var data = chart.data;
						var content = '';

						data.labels.forEach(function(label, index) {
							var bgColor = data.datasets[0].backgroundColor[index];

							content += '<span class="chart-legend-item">';
							content += '<i class="chart-legend-indicator" style="background-color: ' + bgColor + '"></i>';
							content += label;
							content += '</span>';
						});

						return content;
					}
				}
			}
		}

		// yAxes
		Chart.scaleService.updateScaleDefaults('linear', {
			gridLines: {
				borderDash: [2],
				borderDashOffset: [2],
				color: (mode == 'dark') ? colors.gray[900] : colors.gray[300],
				drawBorder: false,
				drawTicks: false,
				drawOnChartArea: true,
				zeroLineWidth: 0,
				zeroLineColor: 'rgba(0,0,0,0)',
				zeroLineBorderDash: [2],
				zeroLineBorderDashOffset: [2]
			},
			ticks: {
				beginAtZero: true,
				padding: 10,
				callback: function(value) {
					if (!(value % 10)) {
						return value
					}
				}
			}
		});

		// xAxes
		Chart.scaleService.updateScaleDefaults('category', {
			gridLines: {
				drawBorder: false,
				drawOnChartArea: false,
				drawTicks: false
			},
			ticks: {
				padding: 20
			},
			maxBarThickness: 10
		});

		return options;

	}

	// Parse global options
	function parseOptions(parent, options) {
		for (var item in options) {
			if (typeof options[item] !== 'object') {
				parent[item] = options[item];
			} else {
				parseOptions(parent[item], options[item]);
			}
		}
	}

	// Push options
	function pushOptions(parent, options) {
		for (var item in options) {
			if (Array.isArray(options[item])) {
				options[item].forEach(function(data) {
					parent[item].push(data);
				});
			} else {
				pushOptions(parent[item], options[item]);
			}
		}
	}

	// Pop options
	function popOptions(parent, options) {
		for (var item in options) {
			if (Array.isArray(options[item])) {
				options[item].forEach(function(data) {
					parent[item].pop();
				});
			} else {
				popOptions(parent[item], options[item]);
			}
		}
	}

	// Toggle options
	function toggleOptions(elem) {
		var options = elem.data('add');
		var $target = $(elem.data('target'));
		var $chart = $target.data('chart');

		if (elem.is(':checked')) {

			// Add options
			pushOptions($chart, options);

			// Update chart
			$chart.update();
		} else {

			// Remove options
			popOptions($chart, options);

			// Update chart
			$chart.update();
		}
	}

	// Update options
	function updateOptions(elem) {
		var options = elem.data('update');
		var $target = $(elem.data('target'));
		var $chart = $target.data('chart');

		// Parse options
		parseOptions($chart, options);

		// Toggle ticks
		toggleTicks(elem, $chart);

		// Update chart
		$chart.update();
	}

	// Toggle ticks
	function toggleTicks(elem, $chart) {

		if (elem.data('prefix') !== undefined || elem.data('prefix') !== undefined) {
			var prefix = elem.data('prefix') ? elem.data('prefix') : '';
			var suffix = elem.data('suffix') ? elem.data('suffix') : '';

			// Update ticks
			$chart.options.scales.yAxes[0].ticks.callback = function(value) {
				if (!(value % 10)) {
					return prefix + value + suffix;
				}
			}

			// Update tooltips
			$chart.options.tooltips.callbacks.label = function(item, data) {
				var label = data.datasets[item.datasetIndex].label || '';
				var yLabel = item.yLabel;
				var content = '';

				if (data.datasets.length > 1) {
					content += '<span class="popover-body-label mr-auto">' + label + '</span>';
				}

				content += '<span class="popover-body-value">' + prefix + yLabel + suffix + '</span>';
				return content;
			}

		}
	}


	// Events

	// Parse global options
	if (window.Chart) {
		parseOptions(Chart, chartOptions());
	}

	// Toggle options
	$toggle.on({
		'change': function() {
			var $this = $(this);

			if ($this.is('[data-add]')) {
				toggleOptions($this);
			}
		},
		'click': function() {
			var $this = $(this);

			if ($this.is('[data-update]')) {
				updateOptions($this);
			}
		}
	});


	// Return

	return {
		colors: colors,
		fonts: fonts,
		mode: mode
	};

})();

'use strict';
var SalesChart = (function() {

	// Variables

	var $chart = $('#chart');


	// Methods

	function init($this) {
		var salesChart = new Chart($this, {
			type: 'line',
			options: {
				scales: {
					yAxes: [{
						gridLines: {
              color: Charts.colors.gray[200],
							zeroLineColor: Charts.colors.gray[200]
						},
						ticks: {

						}
					}]
				}
			},
			data: <?= $data; ?>
		});

		// Save to jQuery object

		$this.data('chart', salesChart);

	};


	// Events

	if ($chart.length) {
		init($chart);
	}

})();
</script>
