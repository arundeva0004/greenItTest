import {Component, forwardRef, OnInit} from '@angular/core';
import {ApiService} from "../../service/api.service";
import {Router} from "@angular/router";
import {ControlValueAccessor, NG_VALUE_ACCESSOR} from '@angular/forms';
import {NotificationService} from "../../service/notification.service";

const INLINE_EDIT_CONTROL_VALUE_ACCESSOR = {
  provide: NG_VALUE_ACCESSOR,
  useExisting: forwardRef(() => DashboardComponent),
  multi: true
};
@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.css'],
  providers: [INLINE_EDIT_CONTROL_VALUE_ACCESSOR],
})
export class DashboardComponent implements OnInit {

  csvData : any = [];
  public showForm : boolean = false;
  constructor(private apiService: ApiService, private router: Router, private notifyService : NotificationService) { }

  ngOnInit(): void {
    this.load();

  };

  load() : void{
    this.apiService.loadCSVData().subscribe((data: object) => {
      this.csvData = data;
      const newArray = [];
      for (let entry of this.csvData) {
        entry.edit= false;
        newArray.push(entry)
       }
      this.csvData = newArray;

    });
  }
      addNew() : void{
        this.showForm = true;
      }
      editRow(data: any, index : number): void {
        this.csvData[index].edit = true;
        //this.router.navigateByUrl(`edit/${data.id}`);
      }

      onCancel(data: any, index : number) : void{
        this.csvData[index].edit = false;
      }

      onUpdate(data: any, index : number) : void{
        this.csvData[index] = data;
        this.csvData[index].edit = false;
        this.notifyService.showSuccess("Updated successfully !!", "CSV File Data");
      }

      addNewData(data : any): void {

        this.showForm = false;
        data.id = this.csvData.length + 1;
        this.apiService.addNew(data).subscribe( (res) => {
          this.notifyService.showSuccess("New Added successfully !!", "CSV File Data");
          this.load();
        },
        (err) => {
          this.notifyService.showSuccess("Failed to add new data!!", "CSV File Data");
        }
        )

      }

      deleteRow(data: any, index : number): void{
        this.csvData.splice(index,1);
        this.notifyService.showSuccess("Deleted successfully !!", "CSV File Data");
      }


}
