import {Component, forwardRef, OnInit} from '@angular/core';
import {ApiService} from "../../service/api.service";
import { Validators, FormGroup, FormControl} from '@angular/forms';
import {NotificationService} from "../../service/notification.service";
import {
  GridComponent,
  CancelEvent,
  EditEvent,
  RemoveEvent,
  SaveEvent
} from "@progress/kendo-angular-grid";
@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.css']
})

export class DashboardComponent implements OnInit {

  public showForm: boolean = false;
  public CsvGridForm: boolean = false;
  public gridView: any = [];
  public gridData: any = [];
  public CsvGridFormGroup: any;
  private editedRowIndex: number | undefined;

  constructor(private apiService: ApiService, private notifyService: NotificationService) {
  }


  csvDataForm = new FormGroup({
    id: new FormControl('', [Validators.nullValidator]),
    name: new FormControl('', [Validators.required]),
    state: new FormControl('', [Validators.required]),
    zip: new FormControl('', [Validators.required]),
    amount: new FormControl('', [Validators.required]),
    qty: new FormControl('', [Validators.required]),
    item: new FormControl('', [Validators.required])
  })

  ngOnInit(): void {
    this.loadCSVData(); //load data from csv file

  };

  /* get list CSV data */
  loadCSVData(): void {
    this.apiService.loadCSVData().subscribe((data: object) => {
        this.gridData = data;

      },
      (err) => {
        this.gridData = [];
        this.notifyService.showError("Failed to load data!!", "CSV File Data");
      });
  }

  /* when user click add button need to show csvDataForm */
  addNew(): void {
    this.showForm = true;
  }

  /* When user click cancel form will hide */
  hideForm(): void {
    this.showForm = false;
    this.csvDataForm.reset();
  }

  /*Add new data to CSV file */
  addNewData(csvDataForm : any) : void {

    let postData = csvDataForm; // Form value assign
    postData.id = this.gridData.length + 1;//row id create
    this.csvDataForm.reset();//Form data reset
    this.showForm = false; //hide form
    this.apiService.addNewData(postData).subscribe( (res )  => {

        this.notifyService.showSuccess("New data added successfully", "CSV File Data");
        this.loadCSVData();
      },
      (err) => {
        this.csvDataForm.reset();//Form data reset
        this.notifyService.showError(err.error, "CSV File Data");
      }
    )

  }

  /* Kendo grid inline edit form create */
  public editHandler(args: EditEvent): void {

    const { dataItem } = args;
    this.closeEditor(args.sender);

    this.CsvGridFormGroup = new FormGroup({
      id: new FormControl(dataItem.id, [Validators.required]),
      name: new FormControl(dataItem.name, [Validators.required]),
      state: new FormControl(dataItem.state,[Validators.required]),
      amount: new FormControl(dataItem.amount,[Validators.required]),
      qty: new FormControl(dataItem.qty,[Validators.required]),
      item: new FormControl(dataItem.item,[Validators.required]),
      zip: new FormControl(dataItem.zip, [Validators.required])
    });

    this.editedRowIndex = args.rowIndex;
    // put the row in edit mode, with the `CsvGridFormGroup` build above
    args.sender.editRow(args.rowIndex, this.CsvGridFormGroup);
  }

  /*Inline edit cancel handler*/
  public cancelHandler(args: CancelEvent){
    // close the editor for the given row
    this.closeEditor(args.sender, args.rowIndex);
  }

  /* remove csv data from csv file */
  public removeHandler(args: RemoveEvent): void {

    let postData = args.dataItem;
    this.apiService.deleteData(postData).subscribe((res) => {
        this.notifyService.showSuccess("Deleted successfully !!", "CSV File Data");
        this.loadCSVData();
      },
      (err) => {
        this.loadCSVData();
        this.notifyService.showError("Failed to update data!!", "CSV File Data");
      }
    )
  }

  /* cancel in-line edit row */
  private closeEditor(grid: GridComponent, rowIndex = this.editedRowIndex) {
    // close the editor
    grid.closeRow(rowIndex);
    // reset the helpers
    this.editedRowIndex = undefined;
    this.CsvGridFormGroup = undefined;
  }

  /* update the data to csv file */
  public saveHandler({ sender, rowIndex, formGroup, isNew }: SaveEvent): void {

    this.apiService.onUpdate(this.CsvGridFormGroup.value).subscribe( (res) => {
        this.loadCSVData();
        this.notifyService.showSuccess("Updated successfully !!", "CSV File Data");
      },
      (err) => {
        this.loadCSVData();
        this.notifyService.showError(err.error, "CSV File Data");
      }
    )

    sender.closeRow(rowIndex);
  }

  /* @csvDataForm input value get */

  get name() {
    return this.csvDataForm.get('name');
  }

  get item() {
    return this.csvDataForm.get('item');
  }

  get amount() {
    return this.csvDataForm.get('amount');
  }

  get qty() {
    return this.csvDataForm.get('qty');
  }

  get state() {
    return this.csvDataForm.get('state');
  }

  get zip() {
    return this.csvDataForm.get('zip');
  }

}
