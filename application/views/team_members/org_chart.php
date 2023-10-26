<style type="text/css">
	#chart-container {
	  position: relative;
	  height: 100%;
	  border: 1px solid #aaa;
	  margin: 0.5rem;
	  overflow: auto;
	  text-align: center;
	  background: white;
	}
	.orgchart
	{
		background: white;
	}
	.orgchart .node .title
	{
		background-color: #1b629f;
		width: 210px;
		height: 150px;
		font-size: 1.2rem !important;
	}
	.orgchart .hierarchy::before
	{
		border-top: 2px solid rgb(213 108 34 / 80%);
	}
	.orgchart > ul > li > ul li > .node::before {
	    background-color: rgb(198 114 41 / 80%);
	}
	.orgchart ul li .node:hover {
	    background-color: rgb(215 124 31 / 50%);
	}

	.orgchart .node .title {
    
	    font-size: 1.3rem;
	}
	.orgchart .node .content {
	    
	    height: 30px;
	    font-size: 0.925rem;
	    border: 1px solid rgba(217, 83, 79, 0.8);
	    font-weight: bold;
	    padding-top: 5px;
	}
	.oci-chevron-left::before,.oci-chevron-right::before,.oci-chevron-up::before,.oci-chevron-down::before {
	    
	    width: 0.925rem;
	    height: 0.925rem;
	}
	.orgchart .node .topEdge {
	    top: -3px;
	}
	.orgchart .node .rightEdge {
	    right: -3px;
	}
	.orgchart .node .leftEdge {
	    left: -3px;
	}
	.orgchart .node .bottomEdge {
	    bottom: -3px;
	}
	.orgchart .node .content {

	    text-transform: capitalize;
	}
	.orgchart .edge:hover::before {
	    border-color: #000000;
	}
	.orgchart .node .edge::before {
	    border-color: rgb(0 0 0 / 90%);
	}
</style>
<div class="panel">
    <div class="panel-default panel-heading">
        <h4>Organizational Chart </h4>

    </div>
    <pre>
        <?php //echo trim($users_data,"[]"); ?>
    </pre>
    <div class="panel-body">
    	<button id="reload_chart" class="btn btn-default bg-success">Reload</button>
    	<div id="chart-container"></div>
    </div>
</div>
<script type="text/javascript">
	 $(function() {
	
	$("#sidebar").addClass("collapsed");

    //var datascource = 
    // {"id":"27","name":"Mohd Bagi","title":"General Manager","img":"","children":[{"id":"28","name":"Doaa Doaa","title":"Project Coordinator","img":"","children":[{"id":"26","name":"Mohd Ali","title":"Software Developer","img":"","children":[]}]},{"id":"25","name":"Teamway Admin","title":"Admin","img":"","children":[]}]} 
//$yourJson = trim($yourJson, '[]');

    var datascource = <?= trim($users_data,"[]") ?>;

    var oc = $('#chart-container').orgchart({
      'data' : datascource,
      'nodeContent': 'title',
      'nodeID': 'id',
      'img': 'img',
    });

    insertImage();

    $('#reload_chart').on('click', function (argument) {
      oc.init({ 'data': datascource });
      insertImage();
    });
	
	
	$('.node').on('click', function(event) {
		window.open("<?= get_uri('team_members/view/')?>" + $(this).attr('id'));
	});

	function insertImage()
	{
		$( ".node" ).each(function( index ) {
		  //console.log( index + ": " + $( this ).text() );
		  var old_html = $(this).find(".title").html();
		  var image_address = $(this).attr('data-img');
		  var img_html = '<br><img style="width:110px; border-radius: 5px; margin-top: 5px;" src="' + image_address + '"></img>';
		  $(this).find(".title").html(old_html + img_html);
		  
		});
	}
	

    $(window).resize(function() {
      var width = $(window).width();
      if(width > 576) {
        oc.init({'verticalLevel': undefined});
        insertImage();
      } else {
        oc.init({'verticalLevel': 2});
        insertImage();
      }
    });

  });
  
</script>