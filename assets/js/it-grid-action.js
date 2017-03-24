function saveRow(btn, pk, actionUrl, alwaysEdit) {
    var $row = $(btn).closest('tr');    
    var data = $row.find("input, textarea, select").serialize();
    console.log(data);
    $.ajax({
        url: actionUrl,
        type: 'post',
        data: data,
        success: function (response) {
            console.log(response);
            //clear previous error message
            $row.find('.help-block').html('');
            var errors = response.errors;
            if (errors.length == 0) {//no error
                $row.removeClass(itChangeCss);
                updateRowData($row, pk, response.result);
                if (!alwaysEdit) {
                    disableInputs($row);
                    toggleButtons($row);
                }
            } else {
                var keys = Object.keys(errors);
                var firstColumn = '';
                for (i = 0; i < keys.length; i++) {
                    if (firstColumn == '' && keys[i] != '*') {
                        firstColumn = keys[i];
                    }
                    var column = keys[i] == '*' ? firstColumn : keys[i];
                    $row.find('.error-' + column).append(errors[keys[i]][0]).append('<br/>');
                }
            }
        },
    });
    return false
}
function cancelRow(btn, pk, actionUrl, alwaysEdit) {
    var $row = $(btn).closest('tr');
    var id = $row.attr('data-key');
    $.ajax({
        url: actionUrl,
        type: 'post',
        data: pk + '=' + id,
        success: function (response) {
            var result = response.result;
            if (result) {
                //console.log("reload data");
                //clear previous error message
                $row.find('.help-block').html('');
                updateRowData($row, pk, result);
                $row.removeClass(itChangeCss);
                if (!alwaysEdit) {
                    disableInputs($row);
                    toggleButtons($row);
                }
                return;
            }
        },
    });
    return false;
}
function editRow(btn) {
    var $row = $(btn).closest('tr');
    var id = $row.attr('data-key');
    enableInputs($row);
    toggleButtons($row);
    return false;
}
function enableInputs($row) {
    //console.log('enable inputs');
    $row.find('input').removeAttr('readonly');
    $row.find('textarea').removeAttr('readonly');
    $row.find('select').removeAttr('disabled');
}
function disableInputs($row) {
    //console.log('disable inputs');
    $row.find('input').attr('readonly', true);
    $row.find('textarea').attr('readonly', true);
    $row.find('select').attr('disabled', true);
}
function toggleButtons($row, isNewRow) {
    //console.log('toggle buttons');
    if (isNewRow) {
        $row.find('a.btnsave').removeClass('hidden');
        $row.find('a.btncancel').removeClass('hidden');
        $row.find('a.btnedit').addClass('hidden');
        return;
    }
    $row.find('a.btnsave').toggleClass('hidden');
    $row.find('a.btncancel').toggleClass('hidden');
    $row.find('a.btnedit').toggleClass('hidden');
}
String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};
function updateRowData($row, pk, dataObject) {
    var keys = Object.keys(dataObject);
    var id = $row.attr('data-key');
    for (i = 0; i < keys.length; i++) {
        var column = keys[i];
        //assign data-key - hardcode
        if (id == 0 && column.toLowerCase() == pk.toLowerCase()) {
            $row.attr('data-key', dataObject[column]);
        }
        var elemArr = $row.find('.value-' + column);
        if (elemArr.length > 0) {
            var elem = elemArr[0];
            switch (elem.nodeName) {
                case 'INPUT':
                    $(elem).attr('value', dataObject[column]);
                    break;
                case 'TEXTAREA':
                    $(elem).text(dataObject[column]);
                    break;
                case 'SELECT':
                    $(elem).find('option[value="' + dataObject[column] +'"]').attr('selected', true);
                    //$(elem).val(dataObject[column]);
                    break;
                default:
            }
        }
    }
}
function deleteRow(btn, pk, actionUrl) {
    if (!confirm("Are you sure you want to delete this row?")) {
        return false;
    }
    var $row = $(btn).closest('tr');
    var id = $row.attr('data-key');
    if (id == 0) {
        $row.remove();
        return false;
    }
    $.ajax({
        url: actionUrl,
        type: 'post',
        data: pk + '=' + id,
        success: function (response) {
            var result = response.result;
            if (!response.errors) {//delete success
                $row.remove();
            }
        },
    });
    return false;
}
function copyRow(btn, pk) {
    var $row = $(btn).closest('tr');
    cloneRow($row, pk, true);
    return false;
}
function createNewRow(btn, pk) {
    var $container = $(btn).closest('div.grid-view');
    var tbody = $container.find('tbody')[0];
    var rows = $(tbody).find('tr');
    var len = rows.length;
    var lastRow = rows[len - 1];
    cloneRow($(lastRow), pk);
}
function cloneRow($row, pk, isCopy) {
    var firstInput = $row.find('input[name]')[0];
    var firstInputName = $(firstInput).attr('name');
    var pos1 = firstInputName.indexOf('[');
    var pos2 = firstInputName.indexOf(']');
    var oldIndexNo = firstInputName.substring(pos1 + 1, pos2);
    var tbody = $row.closest('tbody')[0];
    var rows = $(tbody).find('tr');
    var len = rows.length;
    var rowHtml = $row.html();
    var oldIndex = '\\[' + oldIndexNo + '\\]';//replace Model[rowIndex][attribute]
    rowHtml = rowHtml.replaceAll(oldIndex, '['+len+']');
    rowHtml = '<tr data-key="0" id="it-row-' + len +'">' + rowHtml + '</tr>';
    $(tbody).append(rowHtml);
    $newRow = $('#it-row-' + len);
    if (isCopy) {
        $newRow.find('.value-' + pk).val('');
    } else {
        $newRow.find('input').attr('value', '');
        $newRow.find('option[selected]').removeAttr('selected');
        $newRow.find('textarea').text('');
    }
    //check if first column is serial - update
    $firstColumn = $newRow.find('td:first');
    if ($firstColumn.children().length == 0) {
        $firstColumn.html(len + 1);
    }
    toggleButtons($newRow, true);
    enableNewSelect2($newRow);
    enableInputs($newRow);
    trackInputChange($newRow);
    if (itChangeCss != null & itChangeCss.length > 0) {
        $newRow.addClass(itChangeCss);
    }
    //update footer
    $newRow.find('input, textarea, select').each(function(index) {
        updatePageSummary($(this));
    });
}
function trackInputChange($row) {
    $row.find('input, textarea, select').on('change', function () {
        var $el = $(this);
        var $row = $el.parent().closest('tr');
        if (itChangeCss != null & itChangeCss.length > 0) {
            $row.addClass(itChangeCss);
        }
        updatePageSummary($el);
    });
}
function enableNewSelect2($row) {
    var s2Arr = $row.find('select[data-s2-options]');
    for (i = 0; i < s2Arr.length; i++) {
        var $ctl = $(s2Arr[i]);
        var newId = Math.random().toString(36).substr(2, 10);
        var krajee_select2 = $ctl.attr('data-krajee-select2');
        var s2_options = $ctl.attr('data-s2-options');
        $ctl.attr('id', newId);
        var td = $ctl.parent();
        $(td).find('span.select2').remove();
        $.when($('#' + newId).select2(window[krajee_select2])).done(initS2Loading(newId, window[s2_options]));
    }
}

function updatePageSummary($el) {
    var elCss = $el.attr('class').split(' ');
    var columnId = '';
    for (i = 0; i < elCss.length; i++) {
        if (elCss[i].indexOf('value-') == 0) {
            columnId = elCss[i];
            break;
        }
    }
    if (columnId.length > 0) {
        var footerId = columnId.replace('value-', 'summary-');
        var $footerEl = $('td[data-field="' + footerId + '"]');
        var fType = $footerEl.attr('data-func');
        var result = 0;
        var count = 0
        $('.' + columnId).each(function(index) {
            count++;
            var val = $(this).val();
            if (val == '') {
                return true;
            }
            val = parseFloat(val);
            switch (fType) {
                case 'f_sum':
                case 'f_avg':
                        result += val;
                    break;
                case 'f_min':
                    if (count == 1) {
                        result = val;                        
                    } else if (val < result) {
                        result = val;
                    }
                    break;
                case 'f_max':
                    if (count == 1) {
                        result = val;
                    } else if (val > result) {
                        result = val;
                    }
                    break;
            }
        });
        if (fType == 'f_avg')
            result = result / count;
        if (fType == 'f_count') {
            $footerEl.text(count);
            return ;
        }
        var format = $footerEl.attr('data-format');
        if (format != null && format.indexOf('decimal') >= 0) {
            format = format.replace('[', '').replace(']', '');
            var formatArr = format.split(',');
            if (formatArr.length > 1) {
                $footerEl.text(result.toFixed(formatArr[1]));
                return;
            }
        }
        $footerEl.text(result);
    }
}
