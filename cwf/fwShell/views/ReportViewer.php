<style>#cboformbodyin{border:none;}</style>
<div id="contents" style="margin: 10px 10px 10px; padding-right: 5px;">
<?php $viewerurl='?r=cwf%2FfwShell%2Freport'; ?>
    <div class="row" id="rptrow1">
        <button  id="btnoptions" onclick="expandOptions();" class="col-md-1 btn btn-default" style="margin-left: 15px">Options</button>
        <h3 class="col-md-6" style="margin-top: 2px" id="rptCaption"><?=$viewForRender->getHeader()?></h3>
        <div class="btn-group" role="group" style="float: right; margin-right: 20px;">
            <button  id="btnrefresh" class="btn btn-default" onclick="refreshClick();">
                <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Refresh
            </button>
            <button id="btnprint" class="btn btn-default" disabled onclick="printClick();">
                <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print
            </button>
            <div class="btn-group" role="group">
                <button id="btnprintoptions" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li><a href="#" onclick="exportClick();">Export to PDF</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row" id="rptrow2">
        <div> 
            <form class="col-md-12" id="rptOptions" name ="rptOptions" method="POST" action="<?= (string)$viewerurl ?>" target="rptContainer" >
                <!-- This is the csrf field --> 
                <input type="hidden" id="_csrf" name="_csrf" value="<?=Yii::$app->request->csrfToken?>">
                <!-- The following are hidden compulsory fields, extracted from xml and rendered --> 
                <input type="hidden" name="xmlPath" id="formName" value="<?php echo $xmlPath ?>" />
                <input type="hidden" name="reqtime" id="reqtime" value="<?php echo time()?>" />
                <!-- All additional fields for the user to set extracted from xml and rendered --> 
                 <?php
                 echo $viewForRender->getForm();
                ?>
            </form>
        </div>
    </div>
    <div id="rptRoot" style="padding: 5px;">
          
    </div>
    <script type="text/javascript" >
        var firsttime = true;
        var rptInfo;
        function refreshClick() {
            var url = $('#rptOptions').attr('action') + '/renderhtml' ;
            var data = $('#rptOptions').serialize();
            data = data.replace(/=on/g,'=1');
            data = data.replace(/=True/g,'=1');
            $('#rptOptions input[type=checkbox]:not(:checked)').each(
                function() {
                        data+= '&'+this.name+'=0';
            });
            $('#rptRoot').html('');
            var contentHeight = $('#content-root').height();
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                beforeSend:function(){coreWebApp.startloading();},
                complete:function(){coreWebApp.stoploading();},
                success: function (resultdata, status, jqXHR) {
                    if(jqXHR.getResponseHeader("Output-Type") == "text/html") {
                        $('#rptRoot').html(resultdata);
                    } else if (jqXHR.getResponseHeader("Output-Type") == "application/json") {
                        rptInfo = $.parseJSON(resultdata);
                        var data = rptInfo.Data;
                        var rptParent = $('<div class="print-preview-wrapper" id="rptParent" name="rptParent"></div>');
                        rptParent.append(rptInfo.PageStyle);
                        for(i=1;i<=rptInfo.PageCount;i++) {
                            var rptPage = $('<div id="rptPage'+i+'" class="print-format"></div>');
                            var rptContainer = $('<div id="t'+i+'"></div>');
                            var prop = 'Page'+i;
                            var html = data[prop];
                            rptContainer.append(html);
                            rptPage.append(rptContainer);
                            rptParent.append(rptPage);
                        }
                        $('#rptRoot').append(rptParent);
                        $('#btnprint').removeAttr('disabled');
                        $('#rptParent').height(contentHeight - $('#rptrow1').height() - 65);
                    }
                },
                error: function (data) {
                    coreWebApp.toastmsg('error','Status','Failed to fetch data',false);
                }
            }); 
            adjustHeight();
            return false;
        }  

        function printClick() {
            var pwin = window.open('');
            var htmldoc = $('<html></html>');
            var head = $('<head>'+rptInfo.PageStyle+'</head>');
            htmldoc.append(head);
            var data = rptInfo.Data;
            // This should be a simple parent div to ensure that it does not take printer page space
            var rptParent = $('<div id="rptParent" name="rptParent"></div>');
            for(i=1;i<=rptInfo.PageCount;i++) {
                var rptPage = $('<div id="rptPage'+i+'" class="print-format"></div>');
                var rptContainer = $('<div id="t'+i+'"></div>');
                var prop = 'Page'+i;
                var table = data[prop];
                rptContainer.append(table);
                rptPage.append(rptContainer);
                rptParent.append(rptPage);
            }
            var body = $('<body></body>');
            body.attr('onload', 'pageLoaded()');
            body.append(rptParent);
            htmldoc.append(body);
            var script=pwin.document.createElement('script');
            script.type = 'text/javascript';
            script.text = 'function pageLoaded() { window.print(); window.close(); }';
            htmldoc.append(script);
            pwin.document.write(htmldoc.html());
            pwin.document.close();
            //pwin.close();
        }

        function exportClick() {
            var url = $('#rptOptions').attr('action') + '/renderpdf' ;
            var data = $('#rptOptions').serialize();
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                beforeSend:function(){coreWebApp.toastmsg('info', 'Export Status', 'Submitting report generation request');},
                complete:function(){coreWebApp.toastmsg('info', 'Export Status', 'Report export process completed', false);},
                success: function (resultdata, status, jqXHR) {
                    if(jqXHR.getResponseHeader("Output-Type") == "text/html") {
                        coreWebApp.toastmsg('error', 'Export Status', resultdata, false);
                    } else if (jqXHR.getResponseHeader("Output-Type") == "application/json") {
                        rptInfo = $.parseJSON(resultdata);
                        var pwin = window.open(rptInfo.ReportRenderedPath);
                    }
                },
                error: function (data) {
                    coreWebApp.toastmsg('error','Status','Failed to fetch data',false);
                }
            }); 
            return false;
        }

        var isExpanded = true;
        function adjustHeight() {
           r1=parseInt($('#rptrow1').height());
           r2=0; 
           cntht=parseInt($('#content-root').height());
           $('#rptParent').height(cntht-r1-r2-25);
           $('#rptrow2').hide('slow');
           $('#rptCaption').hide('slow');
           isExpanded = false;
           if($('#rptParent').not(':visible')){  
               $('#rptParent').show('slow');      
           }
        }

        function expandOptions() {
            if(isExpanded) {
                $('#rptrow2').hide('slow');
                isExpanded = false;
            } else {
                $('#rptrow2').show('slow');
                if(coreWebApp.detectIE()){
                $('#rptParent').hide('slow');}
                isExpanded = true;
            }
        }
    </script>
</div>