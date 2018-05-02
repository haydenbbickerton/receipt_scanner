import json2csv from 'json2csv'

const CsvExport = (data, fields, fileName) => {
    try {
        const json2csvParser = new json2csv.Parser({ fields })
        // console.log(data)
        const result = json2csvParser.parse(data)
        // console.log(csv)
        console.log(fileName)
        // const result = json2csv({ data: data, fields: fields, fieldNames: fieldNames })
        const csvContent = "data:text/csv;charset=GBK,\uFEFF" + result
        const encodedUri = encodeURI(csvContent)
        const link = document.createElement("a")
        link.setAttribute("href", encodedUri)
        link.setAttribute("download", `${(fileName || 'file')}.csv`)
        document.body.appendChild(link) // Required for FF
        link.click(); // This will download the data file named "my_data.csv".
        document.body.removeChild(link) // Required for FF

    } catch (err) {
        // Errors are thrown for bad options, or if the data is empty and no fields are provided.
        // Be sure to provide fields if it is possible that your data array will be empty.
        console.error(err)
    }
}

export default CsvExport
