import { Component,OnInit } from '@angular/core';
import { OrderService } from './service/order.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit {
  title = 'angularDev';
  csvData : any = [];
  constructor() { }
  ngOnInit() {

  }
}
