.PHONY: clean All

All:
	@echo "----------Building project:[ LMS - Debug ]----------"
	@"$(MAKE)" -f  "LMS.mk"
clean:
	@echo "----------Cleaning project:[ LMS - Debug ]----------"
	@"$(MAKE)" -f  "LMS.mk" clean
