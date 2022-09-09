import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";
import { Observable } from  'rxjs';

class id {
}

@Injectable({
  providedIn: 'root'
})
export class ApiService {

  PHP_API_SERVER = "http://localhost/backend/";

  public id: number | undefined;
  constructor(private httpClient : HttpClient) { }

  loadCSVData() : Observable <any> {
    return this.httpClient.get(this.PHP_API_SERVER + 'index.php',{});
  }

  addNew(data: any){
    return this.httpClient.post(this.PHP_API_SERVER + 'file_write_csv.php',JSON.stringify(data));
  }

}
