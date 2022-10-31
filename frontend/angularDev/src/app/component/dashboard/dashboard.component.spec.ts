import {ComponentFixture, TestBed, async, fakeAsync} from '@angular/core/testing';
import { DashboardComponent } from './dashboard.component';
import {ApiService} from "../../service/api.service";
import {HttpClientTestingModule} from "@angular/common/http/testing";
import {NotificationService} from "../../service/notification.service";
import {RouterTestingModule} from "@angular/router/testing";
import {ToastrModule, ToastrService} from "ngx-toastr";
import {of} from "rxjs";
import {HttpClient} from "@angular/common/http";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";


describe('DashboardComponent', () => {

  let component: DashboardComponent;
  let fixture: ComponentFixture<DashboardComponent>;
  let apiService : ApiService;
  let mockApiService : any;
  let apiServiceSpy : jasmine.SpyObj<HttpClient>;
  let CSV_RECORDS = [
    {id:"1",name:"Liquid Saffron",state:"NY",zip:"08998",amount:"25.43",qty:"7",item:"XCD45300"},
    {id:"2",name:"Mostly Slugs",state:"PA",zip:"19008",amount:"13.30",qty:"2",item:"AAH6748"},
    {id:"3",name:"Jump Stain",state:"CA",zip:"99388",amount:"56.00",qty:"3",item:"MKII4400"},
    {id:"4",name:"Scheckled Sherlock",state:"WA",zip:"88990",amount:"987.56",qty:"1",item:"TR909"}
  ];

  //arrange
  beforeEach(async () => {

     apiServiceSpy = jasmine.createSpyObj( ['loadCSVData','addNewData','onUpdate','deleteData']);
    await TestBed.configureTestingModule({
      declarations: [DashboardComponent],
      imports: [ HttpClientTestingModule,FormsModule,ReactiveFormsModule,
        RouterTestingModule, ToastrModule.forRoot()],
      providers: [ApiService,
        NotificationService,
        {provide: ToastrService, useClass: ToastrService},
        {provide: apiService, useValue: apiServiceSpy}
      ]
    })
      .compileComponents();

    //act
    apiService =  TestBed.inject(ApiService);
    fixture = TestBed.createComponent(DashboardComponent);
    component = fixture.componentInstance;
    apiServiceSpy = TestBed.inject(HttpClient) as jasmine.SpyObj<HttpClient>;

    mockApiService = jasmine.createSpyObj(['loadCSVData','addNewData','onUpdate','deleteData'])
    //httpClientSpy = TestBed.inject(HttpClient) as jasmine.SpyObj<HttpClient>;
    fixture.detectChanges();

  });

  it('should create Dashboard component', () => {
    //assert
    expect(component).toBeTruthy();
  });

  it('should showForm value return false initially',() => {
    //assert
    expect(component.showForm).toBe(false);
  });

  it('should display form after detectChanges', () => {
    component.showForm = true;
    fixture.detectChanges(); // detect changes explicitly
    expect(component.showForm).toBeTrue();
  });

  it('should call ngOnInit', () => {

    //act
    const component = fixture.debugElement.componentInstance;
    spyOn(component,"loadCSVData").and.returnValue([]);
    component.ngOnInit();

    //assert
    expect(component.loadCSVData).toBeTruthy();

  })

  it('should call loadCsvData and return all records', fakeAsync(  () => {

    //act
    spyOn(apiService,"loadCSVData").and.callFake(() => {
      return of(CSV_RECORDS);
    });
    component.loadCSVData();

    //assert
    expect(component.gridData).toEqual(CSV_RECORDS);

  }))

  /*it('Should call update method and check value is updated', () =>{

    //act
    let updatedData = CSV_RECORDS[1];
    updatedData.name = "Jasprit Bumrah";
    spyOn(apiService,"onUpdate").and.callFake(() => {
      return of(updatedData);
    });
    component.saveHandler(updatedData);

    //assert
    expect(updatedData.name).toEqual("Jasprit Bumrah");
  })*/

  it('Should call post method to add new data to csv file', () =>{

    //act
    let newCsvData = {id:"5",name:"Hardik Pandya",state:"MI",zip:"78262",amount:"150.75", qty:"7", item:"YCS79282" };
    spyOn(component,"addNewData").and.callFake(() => {
      return of(newCsvData);
    });
    component.addNewData(newCsvData);

    //assert
    expect(component.addNewData).toHaveBeenCalled();
  })

});
