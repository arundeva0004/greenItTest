import { TestBed } from '@angular/core/testing';
import { ApiService } from './api.service';
import {HttpClient, HttpErrorResponse, HttpResponse} from "@angular/common/http";
import {HttpClientTestingModule} from "@angular/common/http/testing";
import {ToastrModule} from 'ngx-toastr';
import {of} from "rxjs";


describe('ApiService', () => {

  let apiService: ApiService;
  let httpClientSpy : jasmine.SpyObj<HttpClient>;

  //Mock CSV Data
  let CSV_RECORDS = [
    {id:"1",name:"Liquid Saffron",state:"NY",zip:"08998",amount:"25.43",qty:"7",item:"XCD45300"},
    {id:"2",name:"Mostly Slugs",state:"PA",zip:"19008",amount:"13.30",qty:"2",item:"AAH6748"},
    {id:"3",name:"Jump Stain",state:"CA",zip:"99388",amount:"56.00",qty:"3",item:"MKII4400"},
    {id:"4",name:"Scheckled Sherlock",state:"WA",zip:"88990",amount:"987.56",qty:"1",item:"TR909"}
  ];

  //arrange
  beforeEach(() => {
    httpClientSpy = jasmine.createSpyObj('HttpClient',['get','put','delete','post']);
    TestBed.configureTestingModule({
      imports : [HttpClientTestingModule,
        ToastrModule.forRoot()],
      providers: [ ApiService,
        {provide: HttpClient, useValue: httpClientSpy}
      ]
    });

    //act
    apiService = TestBed.inject(ApiService);
    httpClientSpy = TestBed.inject(HttpClient) as jasmine.SpyObj<HttpClient>;

  });


  it('Should be created api service', () => {
    //assert
    expect(apiService).toBeTruthy();
  });


  it("Should return an expected list of csv records from the file",(done : DoneFn)=>{

    //act
    httpClientSpy.get.and.returnValue(of(CSV_RECORDS));
    apiService.loadCSVData().subscribe({
      next : (result) => {
        //assert
        expect(result.length).toBe(4);
        done();
      },
      error : () =>{
        done.fail;
      }
    });

    //assert
    expect(httpClientSpy.get).toHaveBeenCalledTimes(1);

  });

  it("Should create a new data", (done : DoneFn)=>{

    //act
    let newCsvData = {id:"5",name:"Hardik Pandya",state:"MI",zip:"78262",amount:"150.75", qty:"7", item:"YCS79282" };

    httpClientSpy.post.and.returnValue(of(newCsvData));
    apiService.addNewData(newCsvData).subscribe({
      next : (result) => {
        //assert
        expect(result).toEqual(newCsvData);
        done();
      },
      error : () =>{
        done.fail;
      }
    })

    //assert
    expect(httpClientSpy.post).toHaveBeenCalledTimes(1);

  });


  it("Should update a csv data with given row id", (done : DoneFn)=>{

    //act
    let updateData = CSV_RECORDS[3];
    updateData.name = "M Karunanidhi Stalin";

    httpClientSpy.put.and.returnValue(of(updateData));
    apiService.onUpdate(updateData).subscribe({
      next : (result) => {

        // @ts-ignore
        //assert
        expect(result.name).toEqual("M Karunanidhi Stalin");
        done();

      },
      error : () =>{
        done.fail;
      }
    })

    expect(httpClientSpy.put).toHaveBeenCalledTimes(1);
  });

  it("Should delete an data given row id", (done : DoneFn)=>{

    //act
    let deleteCsvData = {id:"5",name:"Hardik Pandya",state:"MI",zip:"78262",amount:"150.75", qty:"7", item:"YCS79282" };

    httpClientSpy.delete.and.returnValue(of(new HttpResponse ({
      status: 200
    })));

    apiService.deleteData(deleteCsvData).subscribe({
      next : (result) => {

        //assert
        expect(result.status).toEqual(200);
        done();
      },
      error : () =>{
        done.fail;
      }
    })

    //assert
    expect(httpClientSpy.delete).toHaveBeenCalledTimes(1);
  });

});
