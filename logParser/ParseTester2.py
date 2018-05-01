from LogParser import logReader
import datetime
import matplotlib.pyplot as plt
import os
import fnmatch
import csv
import sys  # To pass/get in PHP variable content

# INITIALYZING & Assigning Variables
start_date = "01/01/1000 00:00:00"
end_date = "12/31/9999 23:59:59"

#PHP_Var1 = sys.argv[1].replace("\\", '/')
#upload_file_dir = PHP_Var1.replace("'", "")


# PHP_Var1 = sys.argv[1].replace("'", "")
# upload_file_dir = PHP_Var1
# print("--------PYTHON OUTPUT-------------")
# print(os.getcwd())
# print(PHP_Var1)
# print(upload_file_dir)


# Given a range, only select errors within the range, includes times equal to end_date
def by_date(start_date, end_date, chart_title, chart_filename, chart_type, find_what):
    errors = find_what
    x = []
    y = []

    # Get the current path/directory of this python file
    current_path = os.getcwd()

    # Check this script current path/directory for all file that have ".text" extension
    path_log_names = [f for f in os.listdir(current_path) if fnmatch.fnmatch(f, '*.txt')]


    # Read and iterate all found csv file names
    for file in path_log_names:
        fr = logReader(file, errors)
        error_log = fr.close()
        start_datetime = datetime.datetime(year=int(start_date[6:10]), month=int(start_date[:2]),
                                       day=int(start_date[3:5]), hour=int(start_date[11:13]),
                                       minute=int(start_date[14:16]), second=int(start_date[17:19]))
        end_datetime = datetime.datetime(year=int(end_date[6:10]), month=int(end_date[:2]),
                                     day=int(end_date[3:5]), hour=int(end_date[11:13]),
                                     minute=int(end_date[14:16]), second=int(end_date[17:19]))
        print("Error:                  |" + "Amount Found")
        print("------------------------------------------")
        for e in error_log:
            error_count = 0
            error_stamp = ""
            for date, time, f in error_log[e]:
                error_datetime = datetime.datetime(year=int(date[6:10]), month=int(date[:2]), day=int(date[3:5]),
                                               hour=int(time[:2]), minute=int(time[3:5]), second=int(time[6:8]))
                if (error_datetime >= start_datetime and error_datetime <= end_datetime):
                    error_count += 1
                    error_stamp += str(date) + "," + str(time) + "\n"
            print(e, " "*(22 - len(e)) ,"|" ,str(error_count))

            x.append(int(error_count))
            y.append(e)
            # graphing with plot graph
            # name x ,y and title labels
            plt.ylabel('Amount Found', fontweight='bold')
            plt.xlabel(chart_type, fontweight='bold')
            plt.title(chart_title, fontsize='12', fontweight='bold')
            # rotate x labels so they dont overlap each other
            plt.xticks(rotation=80)
            plt.yticks(rotation=35)
            width = 0.5
            plt.bar(y, x, width, color="blue")
            for i, v in enumerate(x):
                plt.text(i - 0.1, v + 0.5, str(v), color='red', fontweight='bold')
            plt.tight_layout()
            plt.savefig(chart_filename)
        

# Parse Log For Error Metric
def parse_errors():
    print("Error analysis:")
    errors = [
        ('exception', '', ''),
        ('warn', '', ''),
        ('error', '', ''),
        ('fail', '', ''),
        ('unauthorized', '', ''),
        ('timeout', '', ''),
        ('refused', '', ''),
        ('NoSuchPageException', '', ''),
        ('404', '', ''),
        ('401', '', ''),
        ('500', '', ''),
    ]

    chart_title = 'Total errors based on types of error'
    chart_filename = "errors_chart"
    chart_type = 'Types of errors'
    by_date(start_date, end_date, chart_title, chart_filename, chart_type, errors)



# Parse Log For Usage Metric
def parse_usages():
    print("\n\nUsage Analysis:")
    errors = [
        ('BlueprintController', '', ''),
        ('DockerServerController', '', ''),
        ('DockerVolumeController', '', ''),
        ('ProvisionController', '', ''),
    ]
    chart_title = 'Total usages based on types of usage'
    chart_filename = "usages_chart"
    chart_type = 'Types of usages'
    by_date(start_date, end_date, chart_title, chart_filename, chart_type, errors)



# Output both Error and Usage Metrics
parse_usages()