import { Injectable } from '@angular/core';
import {HttpClient, HttpParams} from "@angular/common/http";
import { Observable } from  'rxjs';
import {CsvData} from "../model/csvData";

@Injectable({
  providedIn: 'root'
})
export class ApiService {

  PHP_API_SERVER = "http://localhost/backend/app/";

  public id: number | undefined;
  constructor(private httpClient : HttpClient) { }

  /* Get list csv records */
  loadCSVData() : Observable<any> {

   /* let httpParams = new HttpParams()
      .set('request_from', 'angular')*/
    return this.httpClient.get(this.PHP_API_SERVER + 'CsvFileContent.php',{});
  }

  /* Add data to csv file */
  addNewData(data: object) {
    return this.httpClient.post(this.PHP_API_SERVER + 'CsvFileContent.php',JSON.stringify(data));
  }

  /* update data to csv file */
  onUpdate (data :object){
    return this.httpClient.put(this.PHP_API_SERVER + 'CsvFileContent.php',JSON.stringify(data));
  }

  /* update delete to csv file */
  deleteData(data: object) : Observable<any>{
    let body= JSON.stringify(data);
    return this.httpClient.delete(this.PHP_API_SERVER + 'CsvFileContent.php',{body});
  }
}
