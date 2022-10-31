import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css']
})
export class HeaderComponent implements OnInit {

  public projectTitle : string = "Green IT Test Application";
  constructor() { }

  ngOnInit(): void {
  }

}
