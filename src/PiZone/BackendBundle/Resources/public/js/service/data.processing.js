var DataProcessing = {
    Normalize: function(data){
        $.each(data, function(i, one){
            data[i].template = FieldDispatcher.GetLayout(one.type);
            if(one.type == 'checkbox'){
                if(one.value == 1)
                    data[i].value = true;
                else
                    data[i].value = false;
            }
            if(one.compound)
                data[i].form = DataProcessing.Normalize(data[i].form);
        });
        return data;
    }
}