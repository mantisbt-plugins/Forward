Forward plugin
=====================

A plugin for MantisBT that allows to forward an issue to any email address.
The email contains (for now):
Summary
Description
Additional information
Steps to reproduce
Duedate
With the customfields, you can add several fields separated with a comma or simply a "*" to process all.
In case a customfield has no value it will be skipped.
Multiple email addresses can be used, simply separate them with a semi-colon (;)
Revision history
1.00	2011-04-19	Initial release
1.01	2011-04-20	Bugfix
1.1.0   			forward issue text is now stored as a bug note
2.02	2018-11-01	Updated for Mantis 2.X
2.10	2019-08-25	Option to include multiple Custom Fields in output
2.11	2024-01-31	Added correct version.txt
2.20	2024-02-04	Added printing of custom-fields and attachment overview
2.21	2024-02-06	Added option to add reporter of bug in CC of mail
					Only use Custom fields visible on the Display screen
					Added option to lock options
					
